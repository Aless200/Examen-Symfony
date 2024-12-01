<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminSuperController extends AbstractController
{
    #[Route('/admin/team', name: 'app_admin_team')]
    public function team(UserRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $repository->findAll();
        $admins = [];
        foreach ($users as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
                $admins[] = $user;
                $pagination = $paginator->paginate($admins, $request->query->getInt('page', 1), 5);
            }
        }
        return $this->render('admin/users/super.html.twig', [
            'admins' => $pagination,
        ]);
    }

    #[Route('/admin/team/removerole/{id}', name: 'app_admin_removerole')]
    public function remove(User $user, EntityManagerInterface $manager): Response
    {
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash(
            'success-admin',
            'Rôles de l\'utilisateur à bien été descendu.'
        );
        return $this->redirectToRoute('app_admin_team');
    }

    #[Route('/admin/team/eyesadmin/{id}', name: 'app_admin_eyesadmin')]
    public function eyesAdmin(User $user, EntityManagerInterface $manager): Response
    {
        $user->setDisabled(!$user->isDisabled());
        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('app_admin_team');
    }
}
