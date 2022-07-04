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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {
    private MessageBusInterface $bus;
    private RequestStack $requestStack;
    private User $user;

    public function __construct(MessageBusInterface $bus, RequestStack $requestStack) {
        $this->bus = $bus;
        $this->requestStack = $requestStack;
        $this->user = $this->requestStack->getSession()->get('user');
    }

    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(BlogPostsQuery $blogPostsQuery): Response {
        $posts = $blogPostsQuery();

        $newBlogPostRequest = new NewBlogPostRequest();
        $form = $this->createForm(BlogPostType::class, $newBlogPostRequest, [
            'attr' => [
                'data-blog-target' => 'form',
            ],
        ]);

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
            return new JsonResponse(['serverError' => 'Not an AJAX request.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (!$this->user instanceof User) {
            return new JsonResponse(
                [
                    'error' => 'You must be logged in to create a blog post.',
                ],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        $newBlogPostRequest = new NewBlogPostRequest();

        // Add data attribute to form so that we can target it in the JS.
        $form = $this->createForm(BlogPostType::class, $newBlogPostRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new NewBlogPostMessage($newBlogPostRequest->title, $newBlogPostRequest->content);
            $envelope = $this->bus->dispatch($message);
            $handledStamp = $envelope->last(HandledStamp::class);
            $result = $handledStamp->getResult();
        }

        if (!$form->isValid()) {
            $errors = [];

            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    foreach ($child->getErrors() as $error) {
                        $error = [
                            'input' => $child->getName(),
                            'message' => $error->getMessage(),
                        ];

                        $errors[] = $error;
                    }
                }
            }
        }

        $result = $result ?? false;
        $errors = $errors ?? [];

        return new JsonResponse(
            [
                'success' => $result,
                'errors' => $errors,
                'result' => $result
                    ? [
                        'title' => $newBlogPostRequest->title,
                        'content' => $newBlogPostRequest->content,
                        'author' => $this->user->getUsername(),
                    ]
                    : false,
            ],
            Response::HTTP_CREATED,
        );
    }
}
