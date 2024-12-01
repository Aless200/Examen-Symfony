<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CourseController extends AbstractController
{
    public function __construct(private readonly sluggerInterface $slugger)
    {

    }
    #[Route('/courses', name: 'app_courses')]
    public function courses(CourseRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $course = $repository->findBy(
            ['is_published' => true],
        );
        $pagination = $paginator->paginate($course, $request->query->getInt('page', 1), 6);
        return $this->render('course/courses.html.twig', [
            'course' => $pagination,
        ]);
    }

    #[Route('/course/{slug}', name: 'app_course')]
    public function course(string $slug, CourseRepository $repository,CommentRepository $commentRepository, Request $request, Security $security, EntityManagerInterface $manager): Response
    {
        $course = $repository->findOneBy(['slug' => $slug]);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $user = $security->getUser();
            if (!$user) {
                return $this->redirectToRoute('app_login');
            }
            else {
                $comment
                    ->setUser($user)
                    ->setSend(false)
                    ->setCourse($course)
                    ->setPublished(false)
                    ->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($comment);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Votre commentaire à été soumis.'
                );
            }
            return $this->redirectToRoute('app_course', ['slug' => $slug]);
        }
        $user = $security->getUser();
        $userComment = null;
        if ($user) {
            $userComment = $commentRepository->findOneBy(['course' => $course, 'user' => $user]);
        }
        return $this->render('course/course.html.twig', [
            'course' => $course,
            'commentForm' => $commentForm,
            'userComment' => $userComment,
        ]);
    }
}
