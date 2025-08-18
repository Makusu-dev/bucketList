<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



#[Route('/wish', name: 'wish')]
final class WishController extends AbstractController
{
    #[Route('/list', name: '_list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();

        // et on veut n'afficher que les wish publiés :
        //$wishesPublished = $wishRepository->findBy(['isPublished' => true]);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/{id}', name: '_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish){
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }

        return $this->render('wish/detail.html.twig', [
            'controller_name' => 'WishController',
            'id' => $id,
            'wish' => $wish,
        ]);
    }

    #[Route('/{author}', name: '_byAuthor', requirements: ['author' => '\D+'], methods: ['GET'])]
    public function byAuthor(string $author, WishRepository $wishRepository): Response
    {
        $wishlistByAuthor = $wishRepository->findBy(['author' => $author]);

        if (!$wishlistByAuthor){
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }

        return $this->render('wish/list.html.twig', [
            'controller_name' => 'WishController',
            'wishes' => $wishlistByAuthor,
        ]);
    }

}