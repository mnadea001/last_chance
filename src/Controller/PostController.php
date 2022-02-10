<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Types;
use App\Form\PostType;
use App\Entity\Category;
use App\Service\UploaderHelper;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $arrayPosts = $postRepository->findBy([], ['id' => 'DESC']);
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
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

    #[Route('/{id}', name: 'post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
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

    // /**
    //  * @Route("/admin/upload/test", name="upload_test")
    //  */
    // public function temporaryUploadAction(Request $request)
    // {
    //     /** @var UploadedFile $uploadedFile */
    //     $uploadedFile = $request->files->get('image');
    //     $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
    //     $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
    //     $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
    //     dd($uploadedFile->move(
    //         $destination,
    //         $newFilename
    //     ));
    // }
}
