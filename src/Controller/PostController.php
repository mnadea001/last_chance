<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Types;
use App\Form\PostType;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $arrayPosts = $postRepository->findBy([], ['id' => 'DESC']);
        // BARRE DE RECHERCHE
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un mot-clé'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        return $this->renderForm('post/index.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form,
            'posts' => $arrayPosts
        ]);
    }

    // #[Route('/new', name: 'post_new', methods: ['GET', 'POST'])]
    #[Route('/new', name: 'post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Post $post */
            $post = $form->getData();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadPostImage($uploadedFile);
                $post->setImageFilename($newFilename);
            }


            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
            'action' => 'save'
        ]);
    }

    #[Route('/{id}', name: 'post_show', methods: ['GET|POST'])]
    public function show(Post $post, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $comment->setUser($user);
            $comment->setPost($post);
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        // $likes = $post->getLikes();
        $comments = $post->getComments();

        return $this->renderForm('post/show.html.twig', [
            'post' => $post, 'comments' => $comments, 'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'post_edit')]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadPostImage($uploadedFile);
                $post->setImageFilename($newFilename);
            }


            $entityManager->persist($post);

            $entityManager->flush();

            return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
    }
}
