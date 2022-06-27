<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
    public function __construct() {
        //
    }

    /**
     * @Route("/")
     */
    public function __invoke(): Response {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/home/{name}", name="homepage")
     */
    public function index($name = 'test'): Response {
        return $this->render('pages/home/index.html.twig', [
            'message' => "Hello $name!",
        ]);
    }
}
