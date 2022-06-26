<?php

namespace App\Controller\Cat;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FactsController extends AbstractController
{
    private HttpClientInterface $client;
    public array $facts;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->facts = $this->retrieveFacts();
        dump($this->facts);
    }

    /**
     * @Route("/cat-facts", name="cat_facts")
     */
    public function index(): Response
    {
        return $this->render('CatFacts/catfacts.html.twig', [
            'facts' => $this->facts,
        ]);
    }

    /**
     * Retrieve facts from catfact.ninja API
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function retrieveFacts(): array
    {
        $response = $this->client->request('GET', 'https://catfact.ninja/facts');

        if ($response->getStatusCode() === 200) {
            $response->getContent();
            $response = $response->toArray();

            $result = [];
            foreach ($response['data'] as $value) {
                $result[] = $value['fact'];
            }

            return $result;
        } else {
            return [];
        }
    }
}