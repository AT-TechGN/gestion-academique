<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Etudiant;
use App\Entity\Note;
use App\Form\UserTypeForm;
use App\Form\EtudiantTypeForm;
use App\Form\NoteTypeForm;
use App\Entity\Enseignant;
use App\Form\EnseignantTypeForm;
use App\Repository\EnseignantRepository;
use App\Repository\UserRepository;
use App\Entity\Cours;
use App\Form\CoursTypeForm;
use App\Repository\CoursRepository;
use App\Repository\EtudiantRepository;
use App\Repository\NoteRepository;
use App\Entity\Emploi;
use App\Form\EmploiTypeForm;
use App\Entity\Document;
use App\Form\DocumentTypeForm;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\DocumentRepository;
use App\Repository\EmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    // Dashboard
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(
        UserRepository $userRepo,
        CoursRepository $coursRepo,
        EtudiantRepository $etudiantRepo,
        EnseignantRepository $enseignantRepo,
        NoteRepository $noteRepo
    ): Response {
        // Effectifs
        $stats = [
            'users' => $userRepo->count([]),
            'students' => $etudiantRepo->count([]),
            'teachers' => $enseignantRepo->count([]),
            'courses' => $coursRepo->count([])
        ];

        // Performances académiques
        $notes = $noteRepo->findAll();
        $nbNotes = count($notes);
        $somme = 0;
        $nbNotesFaibles = 0;
        foreach ($notes as $note) {
            $somme += $note->getNote();
            if ($note->getNote() < 10) $nbNotesFaibles++;
        }
        $moyenne = $nbNotes > 0 ? round($somme / $nbNotes, 2) : null;

        // Alertes
        $pendingUsers = []; // À adapter selon ta logique (ex: $userRepo->findBy(['isValidated' => false]))
        $coursSansEnseignant = $coursRepo->createQueryBuilder('c')
            ->leftJoin('c.enseignants', 'e')
            ->andWhere('e.id IS NULL')
            ->getQuery()->getResult();

        // Derniers utilisateurs
        $recentUsers = method_exists($userRepo, 'findRecentUsers')
            ? $userRepo->findRecentUsers()
            : $userRepo->findBy([], ['id' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'moyenne' => $moyenne,
            'nbNotesFaibles' => $nbNotesFaibles,
            'pendingUsers' => $pendingUsers,
            'coursSansEnseignant' => $coursSansEnseignant,
            'recentUsers' => $recentUsers,
        ]);
    }

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $userRepo): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepo->findAll()
        ]);
    }

     

     // Ajoute UserPasswordHasherInterface en argument de la méthode :
    #[Route('/user/new', name: 'admin_user_new')]
    /**
     * Crée un nouvel utilisateur.
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function newUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserTypeForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/{id}/edit', name: 'admin_user_edit')]
    public function editUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Utilisateur mis à jour');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé');
        }

        return $this->redirectToRoute('admin_users');
    }

    // ---------------------------
    // CRUD Étudiants
    // ---------------------------
    #[Route('/students', name: 'admin_students')]
    public function students(EtudiantRepository $etudiantRepo): Response
    {
        return $this->render('admin/students/index.html.twig', [
            'students' => $etudiantRepo->findAll()
        ]);
    }

    #[Route('/student/new', name: 'admin_student_new')]
    public function newStudent(Request $request, EntityManagerInterface $em): Response
    {
        $student = new Etudiant();
        $form = $this->createForm(EtudiantTypeForm::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de la photo');
                }
                $student->setPhoto($newFilename);
            }
            $em->persist($student);
            $em->flush();
            $this->addFlash('success', 'Étudiant créé avec succès');
            return $this->redirectToRoute('admin_students');
        }

        return $this->render('admin/students/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/student/{id}/edit', name: 'admin_student_edit')]
    public function editStudent(Etudiant $student, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EtudiantTypeForm::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de la photo');
                }
                $student->setPhoto($newFilename);
            }
            $em->flush();
            $this->addFlash('success', 'Étudiant mis à jour');
            return $this->redirectToRoute('admin_students');
        }

        return $this->render('admin/students/edit.html.twig', [
            'form' => $form->createView(),
            'student' => $student
        ]);
    }

    #[Route('/student/{id}/delete', name: 'admin_student_delete', methods: ['POST'])]
    public function deleteStudent(Etudiant $student, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $em->remove($student);
            $em->flush();
            $this->addFlash('success', 'Étudiant supprimé');
        }

        return $this->redirectToRoute('admin_students');
    }

    // ---------------------------
    // CRUD Notes
    // ---------------------------
    #[Route('/notes', name: 'admin_notes')]
    public function notes(NoteRepository $noteRepo): Response
    {
        return $this->render('admin/notes/index.html.twig', [
            'notes' => $noteRepo->findAll()
        ]);
    }

    #[Route('/note/new', name: 'admin_note_new')]
    public function newNote(Request $request, EntityManagerInterface $em): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteTypeForm::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($note);
            $em->flush();
            $this->addFlash('success', 'Note ajoutée');
            return $this->redirectToRoute('admin_notes');
        }

        return $this->render('admin/notes/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/note/{id}/edit', name: 'admin_note_edit')]
    public function editNote(Note $note, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(NoteTypeForm::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Note modifiée');
            return $this->redirectToRoute('admin_notes');
        }

        return $this->render('admin/notes/edit.html.twig', [
            'form' => $form->createView(),
            'note' => $note
        ]);
    }

    #[Route('/note/{id}/delete', name: 'admin_note_delete', methods: ['POST'])]
    public function deleteNote(Note $note, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $em->remove($note);
            $em->flush();
            $this->addFlash('success', 'Note supprimée');
        }

        return $this->redirectToRoute('admin_notes');
    }

    // Liste des enseignants
    #[Route('/teachers', name: 'admin_teachers')]
    public function teachers(EnseignantRepository $enseignantRepo): Response
    {
        return $this->render('admin/teachers/index.html.twig', [
            'teachers' => $enseignantRepo->findAll()
        ]);
    }

    // Création
    #[Route('/teacher/new', name: 'admin_teacher_new')]
    public function newTeacher(Request $request, EntityManagerInterface $em): Response
    {
        $teacher = new Enseignant();
        $form = $this->createForm(EnseignantTypeForm::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($teacher);
            $em->flush();
            $this->addFlash('success', 'Enseignant créé avec succès');
            return $this->redirectToRoute('admin_teachers');
        }

        return $this->render('admin/teachers/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Edition
    #[Route('/teacher/{id}/edit', name: 'admin_teacher_edit')]
    public function editTeacher(Enseignant $teacher, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EnseignantTypeForm::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Enseignant mis à jour');
            return $this->redirectToRoute('admin_teachers');
        }

        return $this->render('admin/teachers/edit.html.twig', [
            'form' => $form->createView(),
            'teacher' => $teacher
        ]);
    }

    // Suppression
    #[Route('/teacher/{id}/delete', name: 'admin_teacher_delete', methods: ['POST'])]
    public function deleteTeacher(Enseignant $teacher, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            $em->remove($teacher);
            $em->flush();
            $this->addFlash('success', 'Enseignant supprimé');
        }
        return $this->redirectToRoute('admin_teachers');
    }

    // Liste des emplois du temps
    #[Route('/emplois', name: 'admin_emplois')]
    public function emplois(EmploiRepository $emploiRepo): Response
    {
        return $this->render('admin/emplois/index.html.twig', [
            'emplois' => $emploiRepo->findAll()
        ]);
    }

    // Création
    #[Route('/emploi/new', name: 'admin_emploi_new')]
    public function newEmploi(Request $request, EntityManagerInterface $em): Response
    {
        $emploi = new Emploi();
        $form = $this->createForm(EmploiTypeForm::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($emploi);
            $em->flush();
            $this->addFlash('success', 'Emploi du temps ajouté');
            return $this->redirectToRoute('admin_emplois');
        }

        return $this->render('admin/emplois/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Edition
    #[Route('/emploi/{id}/edit', name: 'admin_emploi_edit')]
    public function editEmploi(Emploi $emploi, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EmploiTypeForm::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Emploi du temps modifié');
            return $this->redirectToRoute('admin_emplois');
        }

        return $this->render('admin/emplois/edit.html.twig', [
            'form' => $form->createView(),
            'emploi' => $emploi
        ]);
    }

    // Suppression
    #[Route('/emploi/{id}/delete', name: 'admin_emploi_delete', methods: ['POST'])]
    public function deleteEmploi(Emploi $emploi, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emploi->getId(), $request->request->get('_token'))) {
            $em->remove($emploi);
            $em->flush();
            $this->addFlash('success', 'Emploi du temps supprimé');
        }
        return $this->redirectToRoute('admin_emplois');
    }

        // Liste des cours
    #[Route('/courses', name: 'admin_courses')]
    public function courses(CoursRepository $coursRepo): Response
    {
        return $this->render('admin/courses/index.html.twig', [
            'courses' => $coursRepo->findAll()
        ]);
    }

    // Création
    #[Route('/course/new', name: 'admin_course_new')]
    public function newCourse(Request $request, EntityManagerInterface $em): Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursTypeForm::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cours);
            $em->flush();
            $this->addFlash('success', 'Cours créé avec succès');
            return $this->redirectToRoute('admin_courses');
        }

        return $this->render('admin/courses/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Edition
    #[Route('/course/{id}/edit', name: 'admin_course_edit')]
    public function editCourse(Cours $cours, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CoursTypeForm::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Cours modifié');
            return $this->redirectToRoute('admin_courses');
        }

        return $this->render('admin/courses/edit.html.twig', [
            'form' => $form->createView(),
            'cours' => $cours
        ]);
    }

    // Suppression
    #[Route('/course/{id}/delete', name: 'admin_course_delete', methods: ['POST'])]
    public function deleteCourse(Cours $cours, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cours->getId(), $request->request->get('_token'))) {
            $em->remove($cours);
            $em->flush();
            $this->addFlash('success', 'Cours supprimé');
        }
        return $this->redirectToRoute('admin_courses');
    }

    // Moyennes générales des notes des étudiants
    // Affiche la moyenne de chaque étudiant
    #[Route('/notes/moyennes', name: 'admin_notes_moyennes')]
    public function notesMoyennes(NoteRepository $noteRepo, EtudiantRepository $etudiantRepo): Response
    {
        $etudiants = $etudiantRepo->findAll();
        $moyennes = [];
        foreach ($etudiants as $etudiant) {
            $notes = $noteRepo->findBy(['etudiant' => $etudiant]);
            $somme = 0;
            $nb = count($notes);
            foreach ($notes as $note) {
                $somme += $note->getNote();
            }
            $moyennes[$etudiant->getId()] = $nb > 0 ? round($somme / $nb, 2) : null;
        }
        return $this->render('admin/notes/moyennes.html.twig', [
            'etudiants' => $etudiants,
            'moyennes' => $moyennes
        ]);
    }

    // Relevé de notes d'un étudiant
    #[Route('/notes/releve/{id}', name: 'admin_notes_releve')]
    public function releveNotes(Etudiant $etudiant, NoteRepository $noteRepo): Response
    {
        $notes = $noteRepo->findBy(['etudiant' => $etudiant]);
        $somme = 0;
        $nb = count($notes);
        foreach ($notes as $note) {
            $somme += $note->getNote();
        }
        $moyenne = $nb > 0 ? round($somme / $nb, 2) : null;
        return $this->render('admin/notes/releve.html.twig', [
            'etudiant' => $etudiant,
            'notes' => $notes,
            'moyenne' => $moyenne
        ]);
    }

    // Publier toutes les notes d'un étudiant
    #[Route('/notes/publish/student/{id}', name: 'admin_notes_publish_student')]
    public function publishNotesByStudent(Etudiant $etudiant, NoteRepository $noteRepo, EntityManagerInterface $em): Response
    {
        $notes = $noteRepo->findBy(['etudiant' => $etudiant]);
        foreach ($notes as $note) {
            $note->setIsPublished(true);
        }
        $em->flush();
        $this->addFlash('success', 'Toutes les notes de cet étudiant ont été publiées.');
        return $this->redirectToRoute('admin_notes');
    }

    // Publier toutes les notes d'un cours
    #[Route('/notes/publish/course/{id}', name: 'admin_notes_publish_course')]
    public function publishNotesByCourse(Cours $cours, NoteRepository $noteRepo, EntityManagerInterface $em): Response
    {
        $notes = $noteRepo->findBy(['cours' => $cours]);
        foreach ($notes as $note) {
            $note->setIsPublished(true);
        }
        $em->flush();
        $this->addFlash('success', 'Toutes les notes de ce cours ont été publiées.');
        return $this->redirectToRoute('admin_notes');
    }

    // Liste des documents
    #[Route('/documents', name: 'admin_documents')]
    public function documents(DocumentRepository $docRepo): Response
    {
        return $this->render('admin/documents/index.html.twig', [
            'documents' => $docRepo->findBy([], ['dateUpload' => 'DESC'])
        ]);
    }

    // Ajout d'un document
    #[Route('/document/new', name: 'admin_document_new')]
    public function newDocument(Request $request, EntityManagerInterface $em): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentTypeForm::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('fichier')->getData();
            if ($file) {
                $filename = uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('documents_directory'), $filename);
                $document->setFichier($filename);
            }
            $em->persist($document);
            $em->flush();
            $this->addFlash('success', 'Document ajouté');
            return $this->redirectToRoute('admin_documents');
        }

        return $this->render('admin/documents/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Téléchargement d'un document
    #[Route('/document/download/{id}', name: 'admin_document_download')]
    public function downloadDocument(Document $document)
    {
        $filepath = $this->getParameter('documents_directory').'/'.$document->getFichier();
        return $this->file($filepath, $document->getTitre());
    }

    // (Optionnel) Suppression d'un document
    #[Route('/document/{id}/delete', name: 'admin_document_delete', methods: ['POST'])]
    public function deleteDocument(Document $document, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $em->remove($document);
            $em->flush();
            $this->addFlash('success', 'Document supprimé');
        }
        return $this->redirectToRoute('admin_documents');
    }

    
}