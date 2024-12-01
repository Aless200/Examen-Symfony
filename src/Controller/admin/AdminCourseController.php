<?php

namespace App\Controller\admin;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminCourseController extends AbstractController
{
    public function __construct(private readonly sluggerInterface $slugger)
    {

    }
    #[Route('/admin', name: 'app_admin')]
    public function courses(CourseRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $courses = $repository->findAll();
        $pagination = $paginator->paginate($courses, $request->query->getInt('page', 1), 10);
        return $this->render('admin/courses/coursesAdmin.html.twig', [
            'courses' => $pagination,
        ]);
    }

    #[Route('/admin/addCourse', name: 'app_admin_addcourse')]
    public function newCourse(Request $request,EntityManagerInterface $manager): Response
    {
        $course = new Course();
        $formadd = $this->createForm(CourseType::class, $course, [
            'is_new' => true,
        ]);
        $formadd->handleRequest($request);
        if ($formadd->isSubmitted() && $formadd->isValid()) {
            $course->setPublished(true)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setSlug($this->slugger->slug($course->getName()));
            $manager->persist($course);
            $manager->flush();
            $this->addFlash(
                'success-admin',
              'La formation à bien été ajouter.',
            );
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/courses/newCourse.html.twig', [
            'formadd' => $formadd,
        ]);
    }

    #[Route('/admin/deleteCourse/{id}', name: 'app_admin_deletecourse')]
    public function deleteCourse(Course $course, EntityManagerInterface $manager): Response
    {
        $manager->remove($course);
        $manager->flush();
        $this->addFlash(
            'success-admin',
            'La formation à bien été supprimer.',
        );
        return $this->redirectToRoute('app_admin');
    }
    #[Route('/admin/eyecourse/{id}', name: 'app_admin_eyecourse')]
    public function eyeCourse(Course $course, EntityManagerInterface $manager): Response
    {
        $course->setPublished(!$course->isPublished());
        $manager->persist($course);
        $manager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/editCourse/{id}', name: 'app_admin_editcourse')]
    public function editCourse(Course $course, EntityManagerInterface $manager, Request $request): Response
    {
        $formEdit = $this->createForm(CourseType::class, $course);
        $formEdit->handleRequest($request);
        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $course->setslug($this->slugger->slug($course->getName()));
            $manager->flush();
            $this->addFlash(
                'success-admin',
                'La formation à bien été modifier.',
            );
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/courses/editCoursesAdmin.html.twig', [
            'formEdit' => $formEdit
        ]);

    }
}
