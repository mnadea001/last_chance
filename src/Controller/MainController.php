<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $arrayPosts = $postRepository->findBy([], ['id' => 'DESC']);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $arrayPosts
        ]);
    }
}
