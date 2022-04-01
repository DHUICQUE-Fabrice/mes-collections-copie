<?php

namespace App\Service;

use App\Entity\Contact;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactService
{
     private EntityManagerInterface $entityManager;
     private FlashBagInterface $flash;
     private MailerInterface $mailer;

     public function __construct(
         EntityManagerInterface $entityManager,
         FlashBagInterface $flash,
            MailerInterface $mailer)
     {
         $this->flash = $flash;
         $this->entityManager = $entityManager;
         $this->mailer = $mailer;
     }

     public function persistContact(Contact $contact):void
     {
         $contact->setIsSent(false)
             ->setCreatedAt(new DateTime());
         $this->entityManager->persist($contact);
         $this->entityManager->flush();

         $this->flash->add('success', 'Votre message a bien été envoyé, merci.');
     }

    /**
     * @throws TransportExceptionInterface
     */
    public function isSent(Contact $contact):void{
         $contact->setIsSent(true);
         $email = (new Email())
             ->from('aelhan.dev@gmail.com')
             ->to('aelhan.dev@gmail.com')
             ->subject('Nouveau message de ' . $contact->getName() . ' ( ' . $contact->getEmail() . ' )')
             ->text('Message du ' . $contact->getCreatedAt()->format('d/m/Y') . ' : ' . $contact->getMessage());


         $this->entityManager->persist($contact);
         $this->entityManager->flush();
         $this->mailer->send($email);

     }
}