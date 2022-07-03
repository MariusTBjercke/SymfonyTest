<?php

namespace App\Controller\Blog;

use App\CQRS\Query\BlogPostsQuery;
use App\Entity\User;
use App\Form\Blog\BlogPostType;
use App\Message\NewBlogPostMessage;
use App\Request\NewBlogPostRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus) {
        $this->bus = $bus;
    }

    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(BlogPostsQuery $blogPostsQuery): Response {
        $posts = $blogPostsQuery();

        $newBlogPostRequest = new NewBlogPostRequest();
        $form = $this->createForm(BlogPostType::class, $newBlogPostRequest);

        return $this->render('pages/blog/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/new", name="blog_post_new", methods={"POST"})
     */
    public function newPost(Request $request): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['error' => 'Not an AJAX request.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $user = $request->getSession()->get('user');

        if (!$user instanceof User) {
            return new JsonResponse(
                [
                    'error' => 'You must be logged in to create a blog post.',
                ],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        $newBlogPostRequest = new NewBlogPostRequest();
        $form = $this->createForm(BlogPostType::class, $newBlogPostRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new NewBlogPostMessage($newBlogPostRequest->title, $newBlogPostRequest->content);
            $envelope = $this->bus->dispatch($message);
            $handledStamp = $envelope->last(HandledStamp::class);
            $result = $handledStamp->getResult();
        }

        $author = $user->getUsername();

        return new JsonResponse(
            [
                'success' => $result ?? false,
                'result' => [
                    'title' => $newBlogPostRequest->title,
                    'content' => $newBlogPostRequest->content,
                    'author' => $author,
                ],
            ],
            Response::HTTP_CREATED,
        );
    }
}
