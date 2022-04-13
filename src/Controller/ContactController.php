<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\AlertServiceInterface;
use App\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request): Response
    {
        $contact = new Contact();
        if($this->getUser()){
            $form = $this->createForm(ContactType::class,$contact,['attr'=> ['userName'=>$this->getUser()->getUsername(),'userEmail'=>$this->getUser()->getEmail()]]);
        }else{
            $form = $this->createForm(ContactType::class,$contact);
        }
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            return $this->redirectToRoute('contact_email',[
                'email' => $contact->getEmail(),
                'message' => $contact->getMessage(),
                'name' => $contact->getName(),
            ]);
        }
        return $this->render('contact/index.html.twig', [
            'form'=>$form->createView(),
            'user'=> $this->getUser(),
        ]);
    }

    /**
     * @param Request $request
     * @param MailerInterface $mailer
     * @param AlertServiceInterface $alertService
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('contact/email', name:'contact_email')]
    public function sendEmailContact(Request $request, MailerInterface $mailer, AlertServiceInterface $alertService): Response
    {
        $email = (new Email())
            ->from($this->getParameter('admin_email'))
            ->to($this->getParameter('admin_email'))
            ->subject('Nouveau message de ' . $request->get('name'))
            ->text($request->get('message') . ' ||||| ' . $request->get('email'));

        $mailer->send($email);
        $alertService->success('Message envoyé avec succès ! Nous vous répondrons dès que possible !');

        return $this->redirectToRoute('contact');
    }
}
