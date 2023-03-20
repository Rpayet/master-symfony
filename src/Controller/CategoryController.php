<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(Request $request, PostRepository $repository, ValidatorInterface $validator): Response
    {


        return $this->render('category/index.html.twig', [
            'categories' =>$repository->findAll(),
        ]);
    }
    

}
