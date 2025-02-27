<?php

namespace App\Controller;



use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'article_list')]
    public function home(ArticleRepository $articleRepository): Response
    {
        // Retrieve all articles from the database
        $articles = $articleRepository->findAll();

        // Render the template with the list of articles
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }


    #[Route('/article/new', name: 'new_article', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('nom', TextType::class)
            ->add('prix', TextType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Créer'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/article/{id}', name: 'article_show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
    
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }
    
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/edit/{id}', name: 'edit_article')]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        // Create a form for editing the article
        $form = $this->createFormBuilder($article)
            ->add('nom', TextType::class, ['label' => 'Nom de l\'article'])
            ->add('prix', TextType::class, ['label' => 'Prix'])
            ->add('save', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the changes
            $entityManager->flush();

            // Redirect to the article list
            return $this->redirectToRoute('article_list');
        }

        // Render the edit form
        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/delete/{id}', name: 'delete_article', methods: ['POST'])]
    public function delete(Article $article, EntityManagerInterface $entityManager): Response
    {
        // Remove the article from the database
        $entityManager->remove($article);
        $entityManager->flush();

        // Redirect to the article list
        return $this->redirectToRoute('article_list');
    }


    
    


}