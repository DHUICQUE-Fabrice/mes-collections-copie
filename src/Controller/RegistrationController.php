<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use App\Service\AlertServiceInterface;
use App\Service\MailjetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('register_email',
            [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserAuthenticator $authenticator
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param MailerInterface $mailer
     * @param AlertServiceInterface $alertService
     * @param User $user
     * @return Response|null
     * @throws TransportExceptionInterface
     */
    #[Route("/register/email/{id}", name:"register_email")]
    public function sendEmailRegister(User $user,
                                      Request $request,
                                      UserAuthenticator $authenticator,
                                      UserAuthenticatorInterface $userAuthenticator,
                                      MailerInterface $mailer,
                                      AlertServiceInterface $alertService
                                      )
    {
        $email = (new Email())
            ->from($this->getParameter('admin_email'))
            ->to($this->getParameter('admin_email'))
            ->subject('Nouvel utilisateur')
            ->text($user->getName() . ' vient de s\'inscrire sur le site');

        $mailer->send($email);
        $alertService->success('Votre compte a bien été créé, bienvenue parmi nous !');

        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request,
        );
    }

    /**
     * @Route("/cgu", name="app_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('registration/cgu.html.twig');
    }
}
