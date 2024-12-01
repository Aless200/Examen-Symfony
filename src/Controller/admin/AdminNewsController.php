<?php

namespace App\Controller\admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminNewsController extends AbstractController
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    #[Route('/admin/news', name: 'app_admin_news')]
    public function news(NewsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $news = $repository->findAll();
        $pagination = $paginator->paginate($news, $request->query->getInt('page', 1), 4);
        return $this->render('admin/news/newsAdmin.html.twig', [
            'news' => $pagination,
        ]);
    }

    #[Route('/admin/eyesnews/{id}', name: 'app_admin_eyesnews')]
    public function eyesNews(News $news, EntityManagerInterface $manager): Response
    {
        $news->setPublished(!$news->isPublished());
        $manager->persist($news);
        $manager->flush();
        return $this->redirectToRoute('app_admin_news');
    }

    #[Route('/admin/deletenews/{id}', name: 'app_admin_deletenews')]
    public function deleteNews(News $news, EntityManagerInterface $manager): Response
    {
        $manager->remove($news);
        $manager->flush();
        $this->addFlash(
            'success-admin',
            'L\'actualité à bien été supprimer.',
        );
        return $this->redirectToRoute('app_admin_news');
    }

    #[Route('/admin/addnews', name: 'app_admin_addnews')]
    public function addNews(Request $request, EntityManagerInterface $manager): Response
    {
        $news = new News();
        $formNews = $this->createForm(NewsType::class, $news, [
            'is_new' => true,
        ]);
        $formNews->handleRequest($request);
        if ($formNews->isSubmitted() && $formNews->isValid()) {
            $news->setPublished(true)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setSlug($this->slugger->slug($news->getName()));
            $manager->persist($news);
            $manager->flush();
            $this->addFlash(
                'success-admin',
              'L\'actualité à bien été ajouter.',
            );
            return $this->redirectToRoute('app_admin_news');
        }
        return $this->render('admin/news/addNews.html.twig', [
            'formNews' => $formNews,
        ]);
    }

    #[Route('/admin/editnews/{id}', name: 'app_admin_editnews')]
    public function editNews(News $news, EntityManagerInterface $manager, Request $request): Response
    {
        $formENews = $this->createForm(NewsType::class, $news);
        $formENews->handleRequest($request);
        if ($formENews->isSubmitted() && $formENews->isValid()) {
            $news->setSlug($this->slugger->slug($news->getName()))
                ->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($news);
            $manager->flush();
            $this->addFlash(
                'success-admin',
              'L\'actualité à bien été modifier.',
            );
            return $this->redirectToRoute('app_admin_news');
        }
        return $this->render('admin/news/editNews.html.twig', [
            'formENews' => $formENews,
        ]);
    }
}

