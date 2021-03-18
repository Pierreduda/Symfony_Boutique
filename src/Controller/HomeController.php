<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $liste_produits = $produitRepository->findAll();
        return $this->render('home/index.html.twig', ["liste_produits" => $liste_produits]);
    }

    #[Route('/home/{id}', name: 'home_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('home/show.html.twig', [
            'produit' => $produit,
        ]);
    }
}
