<?php

namespace App\Controller\Front;


use App\Entity\Listing;
use App\Entity\Brand;
use App\Entity\Modele;
use App\Repository\ListingRepository;
use App\Form\AddListType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    public function __construct(
      private ListingRepository $listingRepository,
    ) { }

    #[Route('/', name: 'app_home')]
    public function home(): Response {

        return $this->render('Front/home.html.twig', [
            'lastLists' => $this->listingRepository->findBy([], ['createdAt' => 'DESC'], 9),
        ]);
    }

#[Route('/{id}', name: 'app_home_redirect')]
    public function redirectToOne($id): Response {
        $list = $this->listingRepository->findOneBy(['id' => $id]);
    return $this->render('Front/showOne.html.twig', [
        'id' => $id,
        'list' => $list,
     ]);
}

#[Route('/new', name: 'app_home_addlist', methods: ['GET', 'POST'])]
public function newList(
    Request $request,
    ListingRepository $listingRepository): Response
{
    $newlist = new Listing();
    $form = $this->createForm(AddListType::class, $newlist);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $listingRepository->save($newlist, true);
    }

    return $this->renderForm('Front/addList.twig', [
        'lists' => $newlist,
        'form' => $form,
    ]);
}
}