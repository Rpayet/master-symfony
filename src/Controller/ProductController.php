<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/create', name: 'app_product_create')]
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // La requête en BDD;
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/create-one', name: 'app_product_create_one')]
    public function createOne(EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $product->setName('Chaise');
        $product->setprice(5999);
        $product->setDescription('Un truc pour s\'asseoir...');
        
        $entityManager->persist($product); // Mets l'objet en attente
        $entityManager->flush(); // INSERT INTO product

        return $this->render('app_product', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $repository) 
    {

        $products = $repository->findAll(); // Donne un tableau avec tous les produits de la BDD

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show')]
    public function show($id, ProductRepository $repository)
    {
        $product = $repository->find($id);

        // 404 si le produit n'existe pas
        if(!$product) {
            throw $this->createNotFoundException();
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
