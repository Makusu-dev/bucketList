<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $wish=new Wish();
        $form=$this->createForm(WishType::class,$wish);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $em->persist($wish);
            $em->flush();
            $this->addFlash('success', 'Your wish has been published!');
//            dd($wish);
            $this->redirectToRoute('wish_detail', ['id'=>$wish->getId()]);
        }

        return $this->render('wish/edit.html.twig', ['wish_form' => $form]);
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