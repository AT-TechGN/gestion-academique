<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Redirection selon le rôle
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        if ($this->isGranted('ROLE_TEACHER')) {
            // Remplace 'teacher_dashboard' par le nom réel de ta route enseignant
            return $this->redirectToRoute('teacher_dashboard');
        }

        if ($this->isGranted('ROLE_STUDENT')) {
            // Remplace 'student_dashboard' par le nom réel de ta route étudiant
            return $this->redirectToRoute('student_dashboard');
        }

        // Par défaut, redirige vers la page de login
        return $this->redirectToRoute('app_login');
    }
}