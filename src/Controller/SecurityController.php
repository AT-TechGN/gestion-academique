<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute($this->getTargetPathBasedOnRole());
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    private function getTargetPathBasedOnRole(): string
    {
        $user = $this->getUser();
        
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return 'admin_dashboard';
        }
        if (in_array('ROLE_TEACHER', $user->getRoles())) {
            return 'teacher_dashboard';
        }
        if (in_array('ROLE_STUDENT', $user->getRoles())) {
            return 'student_dashboard';
        }
        
        return 'app_home';
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method will be intercepted by the logout key on your firewall.');
    }
}