<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/new', name: 'app_post_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $post->setpublishedAt(new DateTimeImmutable());
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Merci '.$post->getName().' pour votre message.');

            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form,
            
        ]);
    }

    #[Route('/post/edit/{id}', name: 'app_post_edit')]
    public function edit(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Merci '.$post->getName().' pour votre message.');

            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }
    
    #[Route('/post/delete/{id}', name: 'app_post_delete')]
    public function delete(Post $post, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_post');
    }

    #[Route('/post/create-one', name: 'app_post_create_one')]
    public function createOne(EntityManagerInterface $entityManager): Response
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
    public function index(PostRepository $repository): Response
    {

        return $this->render('post/index.html.twig', [
            'posts' => $repository->findall(),
        ]);
    }

    
}
