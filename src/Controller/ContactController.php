<?php

namespace App\Controller;

use App\DTO\ContactFormDTO;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contactFormDTO = new ContactFormDTO();

        $form = $this->createForm(ContactType::class, $contactFormDTO);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contactFormDTO = $form->getData();
            
            try {
                $email = (new Email())
                ->from($contactFormDTO->getEmail())
                ->to($contactFormDTO->getService())
                ->subject('Hello from '.$contactFormDTO->getName().' !')
                ->text($contactFormDTO->getMessage())
                ->html('<p>'.$contactFormDTO->getMessage().'</p>');

                $mailer->send($email);
                $this->addFlash('success', 'Votre message a bien été envoyé !');
                return $this->redirectToRoute('app_contact');
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une erreur est survenue lors de l\'envoi du message !');
            }
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
