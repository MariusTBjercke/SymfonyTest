<?php

namespace App\MessageHandler;

use App\Entity\Blog\Post;
use App\Entity\User;
use App\Message\NewBlogPostMessage;
use App\Repository\BlogPostRepository;
use App\Repository\UserRepository;
use DateTime;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class NewBlogPostMessageHandler implements MessageHandlerInterface {
    private BlogPostRepository $postRepository;
    private EventDispatcherInterface $dispatcher;
    private RequestStack $requestStack;
    private UserRepository $userRepository;

    public function __construct(
        BlogPostRepository $repository,
        EventDispatcherInterface $dispatcher,
        RequestStack $requestStack,
        UserRepository $userRepository,
    ) {
        $this->postRepository = $repository;
        $this->dispatcher = $dispatcher;
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user.
     *
     * @param NewBlogPostMessage $message
     * @return bool Return true if the blog post was created.
     */
    public function __invoke(NewBlogPostMessage $message): bool {
        $sessionUser = $this->requestStack->getSession()->get('user');

        if (!$sessionUser instanceof User) {
            return false;
        }

        $user = $this->userRepository->findByIdOrThrow($sessionUser->getId());

        // Create blog post
        $post = (new Post())
            ->setTitle($message->getTitle())
            ->setContent($message->getContent())
            ->setAuthor($user)
            ->setPublished(true)
            ->setPublishedAt(new DateTime())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $this->postRepository->add($post, true);

        return true;
    }
}
