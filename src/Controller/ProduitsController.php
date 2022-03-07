<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsFormType;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\HelperControllers\HelperController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produits', name: 'produits_')]
class ProduitsController extends AbstractController
{

    public function __construct(private ProduitsRepository $produitsRepository, private HelperController $helper)
    {}
    
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('produits/index.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }

    #[Route('/edit/{id}', name: 'modification')]
    public function modificationProduit(int $id, Request $request): Response
    {
        $produit = $this->produitsRepository->findOneById($id);
        $form = $this->createForm(ProduitsFormType::class);
        $form->get('fournisseur')->setData($produit->getFournisseurs()[0]->getLibelle());
        $form->get('categorie')->setData($produit->getCategories()[0]->getNom());
        $form->get('nom')->setData($produit->getNom());
        $form->get('qty')->setData($produit->getQty());
        $form->get('prix')->setData($produit->getPrix());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurName = $form->get('fournisseur')->getData();
            $qty = $form->get('qty')->getData();
            $prixProduit = $form->get('prix')->getData();
            $name = $form->get('nom')->getData();
            $category = $form->get('categorie')->getData();
            $response = $this->forward('App\Controller\ApiControllers\Produits\ProduitsApiController::patchProduit', [
                'id'=>$id,
                'fournisseur'=>$fournisseurName,
                'qty'=>$qty,
                'categorie'=>$category,
                'nom'=>$name,
                'prix'=>$prixProduit
            ]);
            dd($response);
            return $this->redirectToRoute('produits_index');
        }

        $form->handleRequest($request);
        return $this->render('produits/editProduit.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/create', name: 'creation')]
    public function creationProduit(Request $request): Response
    {
        $form = $this->createForm(ProduitsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurName = $form->get('fournisseur')->getData();
            $qty = $form->get('qty')->getData();
            $name = $form->get('nom')->getData();
            $category = $form->get('categorie')->getData();
            $category = $form->get('categorie')->getData();
            $prix = $form->get('prix')->getData();
            $response = $this->forward('App\Controller\ApiControllers\Produits\ProduitsApiController::postProduit', [
                'fournisseur'=>$fournisseurName,
                'qty'=>$qty,
                'categorie'=>$category,
                'nom'=>$name,
                'prix'=>$prix
            ]);
            dd($response);
            return $this->redirectToRoute('produits_index');
        }
        return $this->render('produits/createProduit.html.twig', [
            'form'=>$form->createView(),
            'controller_name' => 'ProduitsController',
        ]);
    }
    
    #[Route('/delete/{id}', name: 'suppression')]
    public function suppressionProduit(int $id): Response
    {
        $response = $this->forward('App\Controller\ApiControllers\Produits\ProduitsApiController::deleteProduit', [
            'id'=>$id,
        ]);
        dd($response);
        return $this->redirectToRoute('produits_index');
    }

    #[Route('/shop', name : 'liste')]
    public function listProduit(Request $request): Response
    {
        $response = $this->forward('App\Controller\ApiControllers\Produits\ProduitsApiController::getAllProduits');
        dd($response);
        return $this->render('produits/shop.html.twig');
    }

    #[Route('/details/{id}', name : 'details')]
    public function infoProduit(Request $request, int $id): Response
    {
        return $this->render('produits/detailsProduit.html.twig');
    }
}