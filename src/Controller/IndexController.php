<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    // #[Route('/', name: 'home')]
/* public function home(): Response
{
    $articles = [
        ['title' => 'Article 1', 'content' => 'Content 1'],
        ['title' => 'Article 2', 'content' => 'Content 2'],
    ];

    return $this->render('articles/index.html.twig', [
        'articles' => $articles,
    ]);
}
 */


#[Route('/{name}', name: 'home_name')]

public function home($name)
{
return $this->render('articles/name.html.twig',['name' => $name]);
}
}