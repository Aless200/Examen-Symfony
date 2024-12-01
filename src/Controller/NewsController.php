<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function news(NewsRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $news = $repository->findBy(
            ['is_published' => 1],
            ['createdAt' => 'DESC'],
        );
        $pagination = $paginator->paginate($news, $request->query->getInt('page', 1), 4);
        return $this->render('news/news.html.twig', [
            'news' => $pagination,
        ]);
    }
}
