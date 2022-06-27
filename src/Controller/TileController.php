<?php

namespace App\Controller;

use App\Entity\Tile;
use App\Form\TileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tile')]
class TileController extends AbstractController
{
    #[Route('/', name: 'app_tile_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tiles = $entityManager
            ->getRepository(Tile::class)
            ->findAll();

        return $this->render('tile/index.html.twig', [
            'tiles' => $tiles,
        ]);
    }

    #[Route('/new', name: 'app_tile_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tile = new Tile();
        $form = $this->createForm(TileType::class, $tile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tile);
            $entityManager->flush();

            return $this->redirectToRoute('app_tile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tile/new.html.twig', [
            'tile' => $tile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tile_show', methods: ['GET'])]
    public function show(Tile $tile): Response
    {
        return $this->render('tile/show.html.twig', [
            'tile' => $tile,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tile $tile, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TileType::class, $tile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tile/edit.html.twig', [
            'tile' => $tile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tile_delete', methods: ['POST'])]
    public function delete(Request $request, Tile $tile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tile->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tile_index', [], Response::HTTP_SEE_OTHER);
    }
}
