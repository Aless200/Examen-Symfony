<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $repository->findAll();
        $roleUser = [];
        foreach ($users as $user) {
            if (in_array('ROLE_USER', $user->getRoles())) {
                $roleUser[] = $user;
            }
        }
        $user = $paginator->paginate($roleUser, $request->query->getInt('page', 1), 10);

        return $this->render('admin/users/users.html.twig', [
            'users' => $user,
        ]);
    }

    #[Route('/admin/addusers', name: 'app_admin_add_user')]
    public function addUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager, Security $security): Response
    {
        $user = new User();
        $formAdminAdd = $this->createForm(RegistrationFormType::class, $user, [
            'is_registration' => true,
        ]);
        $formAdminAdd->handleRequest($request);
        if ($formAdminAdd->isSubmitted() && $formAdminAdd->isValid()) {
            $plainPassword = $formAdminAdd->get('plainPassword')->getData();
            $user
                ->setPassword($userPasswordHasher->hashPassword($user, $plainPassword))
                ->setRoles(['ROLE_USER'])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setDisabled(false);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success-admin',
                'Le compte à bien été ajouter.'
            );
            return $this->redirectToRoute('app_admin_users');
            return $security->login($user, 'form_login', 'main');

        }
        return $this->render('admin/users/addUser.html.twig', [
            'formAdminAdd' => $formAdminAdd,
        ]);
    }

    #[Route('/admin/promoteuser\{id}', name: 'app_admin_promoteuser')]
    public function promoteUser(User $user, EntityManagerInterface $manager): Response
    {
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash(
            'success-admin',
            'Utilisateur promu avec succes.'
        );
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/admin/eyesuser/{id}', name: 'app_admin_eyesuser')]
    public function eyeUser(User $user, EntityManagerInterface $manager): Response
    {
        $user->setDisabled(!$user->isDisabled());
        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('app_admin_users');
    }
}
