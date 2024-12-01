<?php

namespace App\Controller\admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCommentsController extends AbstractController
{
    #[Route('/admin/comments', name: 'app_admin_comments')]
    public function comments(CommentRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $comments = $repository->findAll();
        $pagination = $paginator->paginate($comments, $request->query->getInt('page', 1), 10);
        return $this->render('admin/comment/comments.html.twig', [
            'comments' => $pagination,
        ]);
    }

    #[Route('/admin/comments/eyescomment/{id}', name: 'app_admin_eyescomment')]
    public function eyescomment(Comment $comment, EntityManagerInterface $manager): Response
    {
        $comment->setPublished(!$comment->isPublished());
        $manager->persist($comment);
        $manager->flush();
        return $this->redirectToRoute('app_admin_comments');
    }

    #[Route('/admin/comments/delete/{id}', name: 'app_admin_commentsdelete')]
    public function deleteComm(Comment $comment, EntityManagerInterface $manager): Response
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
            'success-admin',
            'Commentaire supprimé avec succes.'
        );
        return $this->redirectToRoute('app_admin_comments');
    }
}
