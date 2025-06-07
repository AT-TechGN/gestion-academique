<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Repository\NoteRepository;
use App\Repository\DocumentRepository;
use App\Repository\EmploiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;

#[Route('/student')]
#[IsGranted('ROLE_STUDENT')]
class StudentController extends AbstractController
{
    #[Route('/dashboard', name: 'student_dashboard')]
    public function dashboard(
        CoursRepository $coursRepo,
        NoteRepository $noteRepo,
        DocumentRepository $docRepo,
        EmploiRepository $emploiRepo
    ): Response {
        $user = $this->getUser();
        $etudiant = ($user instanceof User) ? $user->getEtudiant() : null;

        // Récupère les cours via l'entité Etudiant
        $cours = $etudiant ? $etudiant->getCours() : [];

        // Notes de l'étudiant
        $notes = $noteRepo->findBy(['etudiant' => $etudiant]);

        // Documents liés aux cours de l'étudiant
        $documents = [];
        if ($cours) {
            $documents = $docRepo->createQueryBuilder('d')
                ->where('d.cours IN (:cours)')
                ->setParameter('cours', $cours)
                ->getQuery()
                ->getResult();
        }

        // Emplois liés aux cours de l'étudiant
        $emplois = [];
        if ($cours) {
            $emplois = $emploiRepo->createQueryBuilder('em')
                ->where('em.cours IN (:cours)')
                ->setParameter('cours', $cours)
                ->getQuery()
                ->getResult();
        }

        return $this->render('student/dashboard.html.twig', [
            'cours' => $cours,
            'notes' => $notes,
            'documents' => $documents,
            'emplois' => $emplois,
        ]);
    }

    #[Route('/courses', name: 'student_courses')]
    public function courses(): Response
    {
        $user = $this->getUser();
        $etudiant = ($user instanceof User) ? $user->getEtudiant() : null;
        $cours = $etudiant ? $etudiant->getCours() : [];

        return $this->render('student/courses.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/notes', name: 'student_notes')]
    public function notes(NoteRepository $noteRepo): Response
    {
        $user = $this->getUser();
        $etudiant = ($user instanceof User) ? $user->getEtudiant() : null;
        $notes = $noteRepo->findBy(['etudiant' => $etudiant]);
        return $this->render('student/notes.html.twig', [
            'notes' => $notes,
        ]);
    }

    #[Route('/documents', name: 'student_documents')]
    public function documents(DocumentRepository $docRepo): Response
    {
        $user = $this->getUser();
        $etudiant = ($user instanceof User) ? $user->getEtudiant() : null;
        $cours = $etudiant ? $etudiant->getCours() : [];
        $documents = [];
        if ($cours) {
            $documents = $docRepo->createQueryBuilder('d')
                ->where('d.cours IN (:cours)')
                ->setParameter('cours', $cours)
                ->getQuery()
                ->getResult();
        }

        return $this->render('student/documents.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/emplois', name: 'student_emplois')]
    public function emplois(EmploiRepository $emploiRepo): Response
    {
        $user = $this->getUser();
        $etudiant = ($user instanceof User) ? $user->getEtudiant() : null;
        $cours = $etudiant ? $etudiant->getCours() : [];
        $emplois = [];
        if ($cours) {
            $emplois = $emploiRepo->createQueryBuilder('em')
                ->where('em.cours IN (:cours)')
                ->setParameter('cours', $cours)
                ->getQuery()
                ->getResult();
        }

        return $this->render('student/emplois.html.twig', [
            'emplois' => $emplois,
        ]);
    }

    #[Route('/profile', name: 'student_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        $etudiant = ($user instanceof User) ? $user->getEtudiant() : null;
        return $this->render('student/profile.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }
}