<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{

    /**
     * @Route ("/login", name="app_login")
     */
    public function loginPage()
    {
        $data = "da";
        return $this->render('login.html.twig', ['data' => $data]);
    }

}