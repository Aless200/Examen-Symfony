<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/editprofile', name: 'app_edit_profile')]
    public function editProfile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $formEditP = $this->createForm(RegistrationFormType::class, $user);
        $formEditP->handleRequest($request);
        if ($formEditP->isSubmitted() && $formEditP->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre profil à bien été modifier.'
            );
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('profile/editprofile.html.twig', [
            'formEditP' => $formEditP,
        ]);
    }

    #[Route('/editpassword', name: 'app_edit_password')]
    public function editPassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $formPassword = $this->createForm(ChangePasswordType::class, $user, [
            'edit' => true
        ]);
        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $oldPassword = $formPassword->get('oldPassword')->getData();
            if (!$userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash(
                    'error',
                    'Mot de passe incorrect.'
                );
                return $this->render('profile/editpassword.html.twig', [
                    'formPassword' => $formPassword,
                ]);
            }

            $plainPassword = $formPassword->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Mot de passe modifié.'
            );
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('profile/editpassword.html.twig', [
            'formPassword' => $formPassword,
        ]);
    }
}
