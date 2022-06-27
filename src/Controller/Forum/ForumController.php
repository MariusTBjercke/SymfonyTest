<?php

declare(strict_types=1);

namespace App\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forum")
 */
class ForumController extends AbstractController {
    /**
     * @Route("/", name="forum")
     */
    public function index(): Response {
        return $this->render('pages/forum/index.html.twig', [
            'message' => 'Hello world!',
        ]);
    }
}
