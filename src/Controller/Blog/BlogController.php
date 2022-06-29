<?php

namespace App\Controller\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(): Response {
        return $this->render('pages/blog/index.html.twig', [
            //
        ]);
    }

    /**
     * @Route("/new", name="blog_new", methods={"GET, POST"})
     */
    public function newPost(): Response {
        return $this->render('pages/blog/new.html.twig', [
            //
        ]);
    }
}
