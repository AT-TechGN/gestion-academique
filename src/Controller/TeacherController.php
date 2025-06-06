<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Repository\EmploiRepository;
use App\Repository\NoteRepository;
use App\Repository\DocumentRepository;
use App\Entity\User;
use App\Entity\Enseignant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/teacher')]
#[IsGranted('ROLE_TEACHER')]
final class TeacherController extends AbstractController
{
    #[Route('/dashboard', name: 'teacher_dashboard')]
    public function dashboard(
        CoursRepository $coursRepo,
        EmploiRepository $emploiRepo,
        NoteRepository $noteRepo,
        DocumentRepository $docRepo
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();
        /** @var Enseignant|null $enseignant */
        $enseignant = $user instanceof User ? $user->getEnseignant() : null;

        if (!$enseignant) {
            $this->addFlash('danger', "Aucun profil enseignant n'est lié à ce compte.");
            return $this->redirectToRoute('app_logout');
        }

        // Cours affectés
        $cours = $coursRepo->createQueryBuilder('c')
            ->join('c.enseignants', 'e')
            ->where('e = :enseignant')
            ->setParameter('enseignant', $enseignant)
            ->getQuery()
            ->getResult();

        // Emplois du temps
        $emplois = $emploiRepo->findBy(['enseignant' => $enseignant]);

        // Notes
        $notes = $noteRepo->findBy(['enseignant' => $enseignant]);
        $nbNotes = count($notes);
        $somme = 0;
        $nbNotesPubliees = 0;
        foreach ($notes as $note) {
            $somme += $note->getNote();
            if (method_exists($note, 'isPublished') && $note->isPublished()) $nbNotesPubliees++;
        }
        $moyenne = $nbNotes > 0 ? round($somme / $nbNotes, 2) : null;

        // Documents pédagogiques
        $documents = [];
        foreach ($cours as $c) {
            foreach ($c->getDocuments() as $doc) {
                $documents[] = $doc;
            }
        }

        // Effectif étudiants
        $effectif = 0;
        foreach ($cours as $c) {
            if (method_exists($c, 'getEtudiants')) {
                $effectif += count($c->getEtudiants() ?? []);
            }
        }

        return $this->render('teacher/dashboard.html.twig', [
            'cours' => $cours,
            'emplois' => $emplois,
            'documents' => $documents,
            'moyenne' => $moyenne,
            'nbNotesPubliees' => $nbNotesPubliees,
            'effectif' => $effectif,
            'stats' => [
                'courses'   => count($cours),
                'students'  => $effectif,
                'documents' => count($documents),
            ],
            'recentCourses' => array_slice($cours, 0, 5),      // Les 5 derniers cours affectés
            'recentDocuments' => array_slice($documents, 0, 5) // Les 5 derniers documents partagés
        ]);
    }

    #[Route('/courses', name: 'teacher_courses')]
    public function courses(CoursRepository $coursRepo): Response
    {
        $enseignant = $this->getUser()?->getEnseignant();
        $cours = $coursRepo->createQueryBuilder('c')
            ->join('c.enseignants', 'e')
            ->where('e = :enseignant')
            ->setParameter('enseignant', $enseignant)
            ->getQuery()
            ->getResult();

        return $this->render('teacher/courses.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/emplois', name: 'teacher_emplois')]
    public function emplois(EmploiRepository $emploiRepo): Response
    {
        $enseignant = $this->getUser()?->getEnseignant();
        $emplois = $emploiRepo->findBy(['enseignant' => $enseignant]);
        return $this->render('teacher/emplois.html.twig', [
            'emplois' => $emplois,
        ]);
    }

    #[Route('/notes', name: 'teacher_notes')]
    public function notes(NoteRepository $noteRepo): Response
    {
        $enseignant = $this->getUser()?->getEnseignant();
        $notes = $noteRepo->findBy(['enseignant' => $enseignant]);
        return $this->render('teacher/notes.html.twig', [
            'notes' => $notes,
        ]);
    }

    #[Route('/documents', name: 'teacher_documents')]
    public function documents(DocumentRepository $docRepo): Response
    {
        $enseignant = $this->getUser()?->getEnseignant();
        $documents = $docRepo->createQueryBuilder('d')
            ->join('d.cours', 'c')
            ->join('c.enseignants', 'e')
            ->where('e = :enseignant')
            ->setParameter('enseignant', $enseignant)
            ->getQuery()
            ->getResult();

        return $this->render('teacher/documents.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/profile', name: 'teacher_profile')]
    public function profile(): Response
    {
        $enseignant = $this->getUser()?->getEnseignant();
        return $this->render('teacher/profile.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }
}