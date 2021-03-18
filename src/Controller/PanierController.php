<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(Session $session): Response
    {
        $panier = $session->get("panier");
        return $this->render('panier/index.html.twig', compact("panier"));
    }

    #[Route('/panier/ajouter/{id}', name: 'panier_ajouter', requirements: ['id' => '\d+'])]
    public function ajouter(Request $request, Session $session, Produit $produit): Response
    {
        $quantite = (int)$request->query->get("quantite");
        $quantite = empty($quantite) ? 1 : $quantite;
        $panier = $session->get("panier", []);
        $produitExiste = false;
        foreach ($panier as $key => $ligne) {
            if ($produit->getId() == $ligne["produit"]->getId()) {
                $panier[$key]["quantite"] += $quantite;
                $produitExiste = true;
            }
        }
        if (!$produitExiste) $panier[] = ["produit" => $produit, "quantite" => $quantite];
        $session->set("panier", $panier);
        $this->addFlash("success", "Le produit " . $produit->getTitre() . " a bien été ajouté $quantite fois à votre panier");
        return $this->redirectToRoute('home');
    }

    #[Route('/panier/supprimer/{id}', name: 'panier_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(Session $session, Produit $produit): Response
    {
        $panier = $session->get("panier", []);
        foreach ($panier as $key => $ligne) {
            if ($produit->getId() == $ligne["produit"]->getId()) {
                unset($panier[$key]);
                break;
            }
        }
        $session->set("panier", $panier);
        $this->addFlash("success", "Le produit " . $produit->getTitre() . " a bien été retiré de votre panier");
        return $this->redirectToRoute('panier');
    }

    #[Route('/panier/vider', name: 'panier_vider')]
    public function vider(Session $session): Response
    {
        $session->remove("panier");
        $this->addFlash("success", "Le panier a été vidé");
        return $this->redirectToRoute('home');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/panier/valider', name: 'panier_valider')]
    public function valider(Session $session, EntityManagerInterface $em, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get("panier");
        $commande = new Commande;
        $commande->setMembre($this->getUser());
        $commande->setDateEnregistrement(new \DateTime());
        $commande->setEtat("en attente");
        $montant = 0;
        foreach ($panier as $ligne) {
            $montant += $ligne["produit"]->getPrix() * $ligne["quantite"];
            $ligne_commande = new LigneCommande;
            $ligne_commande->setCommande($commande);
            // Il ne faut surtout pas utiliser $ligne["produit"] dans setProduit
            // L'entity manager essaierait de créer un nouveau produit bien que $ligne["produit"] ait un id non null
            // Donc on récupère le produit avec le ProduitRepository
            $produit = $produitRepository->find($ligne["produit"]->getId());
            $ligne_commande->setProduit($produit);
            $ligne_commande->setQuantite($ligne["quantite"]);
            $ligne_commande->setPrix($montant);
            $em->persist($ligne_commande);
            $produit->setStock($produit->getStock() - $ligne["quantite"]);
        }
        $commande->setMontant($montant);
        $em->persist($commande);
        $em->flush();
        $session->remove("panier");

        $this->addFlash("success", "Le panier a été validé, nous commençons à préparer votre commande");
        return $this->redirectToRoute('home');
    }
}
