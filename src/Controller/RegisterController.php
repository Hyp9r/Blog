<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    /**
     * @Route ("/register", name="app_register")
     */
    public function showForms()
    {
        return $this->render('register.html.twig');
    }

    /**
     * @Route ("/dd", name="app_register_a")
     */
    public function registerUser()
    {
    }

}