<?php

namespace App\Controller;

use App\Service\MenuBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
    /**
     * @var MenuBuilder
     */
    private $builder;

    /**
     * DashboardController constructor.
     */
    public function __construct(
        MenuBuilder $builder
    ) {
        $this->builder = $builder;
    }

    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

          return $this->render('login/index.html.twig', [
              'last_username' => $lastUsername,
              'error'         => $error,
              'menu' => $this->builder->getMenuData()
          ]);
      }
}
