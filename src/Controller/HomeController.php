<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use http\Client\Request;
use HttpRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session): Response
    {
        return $this->render('home/home.html.twig', ['ajaxUrl' => $this->generateUrl('add_user', [], UrlGeneratorInterface::ABSOLUTE_URL)]);
    }

    /**
     * @Route("/api/add/user", name="add_user", methods={"POST"})
     */
    public function addUser(MessageBusInterface $bus, \Symfony\Component\HttpFoundation\Request $request): JsonResponse
    {
        $isXML = $request->isXmlHttpRequest();
        $reqUrl = $request->getRequestUri();
        $content = json_decode($request->getContent(), true);

        $name = $content['name'];
        $email = $content['email'];
        $age = $content['age'];

        $envelope = $bus->dispatch(new CreateUserMessage($name, $email, (int) $age));

        $handledStamp = $envelope->last(HandledStamp::class);
        $result = $handledStamp->getResult();

        $response = [
            'status' => $result ? 'success' : 'error',
            'message' => $content,
            'stamp_result' => $result,
            'requestUrl' => $reqUrl,
            'isXML' => $isXML
        ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/home/users", name="users")
     */
    public function getUsers(UserRepository $repository): JsonResponse
    {
        $users = $repository->findAll();

        $response = [
            'result' => 'OK',
            'users' => $users,
        ];

        return new JsonResponse($response);
    }

}