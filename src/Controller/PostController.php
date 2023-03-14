<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    #[Route('/post/create', name: 'app_post_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $post->setName('Billy')
            ->setContent('Lorem')
            ->setPublishedAt(new DateTimeImmutable()) // Pour éviter un "Use" rédiger : new /DateTimeImmutable()
            ->setActive(true);

        $entityManager->persist($post);
        $entityManager->flush();
                
        return $this->redirectToRoute('app_post');
    }

    #[Route('/post/one', name: 'app_post_one')]
    public function showOne(ProductRepository $repository, Post $post): Response 
    {
        $post = $repository->find(1);

        // SELECT * FROM post name = 'Billy' ORDER BY id DESC LIMIT 1
        $post = $repository->findOneBy(['name' => 'Billy'], ['id' => 'DESC']);

        if(!$post) {
            throw $this->createNotFoundException('Attention de bien créer un post Billy');
        }
        // Exemple Datetime
        // $date = new DateTimeImmutable();

        // $date = $post->getPublishedAt();

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_show')]
    public function show(Post $post)
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post', name: 'app_post')]
    public function index(ProductRepository $repository): Response
    {

        return $this->render('post/index.html.twig', [
            'post' => $repository->findall(),
        ]);
    }

    
}
