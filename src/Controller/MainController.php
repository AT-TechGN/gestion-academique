<?php
// src/Controller/MainController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Solution robuste pour XAMPP
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Gestion des redirections selon le rôle
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }
        
        if ($this->isGranted('ROLE_TEACHER')) {
            return $this->redirectToRoute('app_teacher');
        }
        
        return $this->redirectToRoute('app_student');
    }
}