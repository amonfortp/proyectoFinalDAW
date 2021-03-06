<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function redirectLogin()
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/login/{exito}", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, string $exito = null)
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->getUser()) {
            return $this->redirectToRoute('app_logout');
        } else {
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'exito' => $exito]);
        }
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
