<?php

namespace App\Controller\Blog;

use App\CQRS\Query\BlogPostsQuery;
use App\Entity\User;
use App\Message\NewBlogPostMessage;
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

        return $this->render('pages/blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/new", name="blog_post_new", methods={"POST"})
     */
    public function newPost(Request $request): JsonResponse {
        $title = $request->request->get('title');
        $content = $request->request->get('content');
        $user = $request->getSession()->get('user');

        if (!$user instanceof User) {
            return new JsonResponse(
                [
                    'error' => 'You must be logged in to create a post.',
                ],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        $author = $user->getUsername();

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['error' => 'Not an AJAX request.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $message = new NewBlogPostMessage($title, $content);
        $envelope = $this->bus->dispatch($message);
        $handledStamp = $envelope->last(HandledStamp::class);
        $result = $handledStamp->getResult();

        return new JsonResponse(
            [
                'success' => (bool) $result,
                'result' => [
                    'title' => $title,
                    'content' => $content,
                    'author' => $author,
                ],
            ],
            Response::HTTP_CREATED,
        );
    }
}
