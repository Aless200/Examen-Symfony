<?php

namespace App\Controller;

use App\Class\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
//    /**
//     * @throws TransportExceptionInterface
//     */
//    #[Route('/contact', name: 'app_contact')]
//    public function contact(MailerInterface $mailer): Response
//    {
//        $email = (new Email())
//            ->from('john.doe@example.com')
//            ->to('info@example.com')
//            ->subject('Question')
//            ->text('Je demande des infos...');
//        $mailer->send($email);
//        return $this->redirectToRoute('app_home');
//    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contact', name: 'app_contact')]
    public function contact(MailerInterface $mailer, Request $request): Response
    {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $email = (new Email())
                ->from($contact->getEmail())
                ->to($contact->getUser()->getEmail())
                ->subject($contact->getSubject())
                ->text($contact->getMessage());
            $mailer->send($email);
            $this->addFlash(
                'success',
                'Votre message à bien été envoyer.'
            );
            return $this->redirectToRoute('app_home');
        }
        return $this->render('contact/contact.html.twig', [
            'formContact' => $formContact,
        ]);
    }
}
