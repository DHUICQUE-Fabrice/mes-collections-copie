<?php

namespace App\Controller;

use App\Form\ForgottenPasswordType;
use App\Repository\UserRepository;
use App\Service\AlertServiceInterface;
use App\Service\MailjetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('accueil');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @Route("/logout", name="app_logout")
     * @return void
     */
    public function logout(): void
    {
        throw new \LogicException();
    }

    /**
     * @Route("/oubli-mot-de-passe", name="app_forgot_password")
     * @param MailerInterface $mailer
     * @param UserRepository $userRepository
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function recoverPassword(MailerInterface $mailer, AlertServiceInterface $alertService, UserRepository $userRepository, Request $request, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForgottenPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->getData()['email']]);
            if (!$user) {
                $alertService->success('Un lien de réinitialisation du mot de passe vous a été envoyé ! (Vérifiez votre dossier spam / courrier indésirable)');
                return $this->redirectToRoute('app_login');
            }
            $token = $tokenGenerator->generateToken();
            try {
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $alertService->danger('Une erreur est survenue, veuillez réessayer plus tard');
                return $this->redirectToRoute('app_login');
            }
            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $mailer = new MailjetService($mailer);
            $mailer->sendEmail('aelhan.dev@gmail.com', $user->getEmail(), 'Réinitialisation du mot de passe', 'Bonjour ' . $user->getName() .  ', vous avez demandé à réinitialiser votre mot de passe sur mes-collections, voici le lien de réinitialisation : ' . $url);
            $alertService->success('Un lien de réinitialisation du mot de passe vous a été envoyé ! (Vérifiez votre dossier spam / courrier indésirable)');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/recoverPassword.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/reset-password/{token}", name="app_reset_password")
     * @param UserPasswordHasherInterface $hasher
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param string $token
     * @return Response
     */
    public function resetPassword(UserPasswordHasherInterface $hasher, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, AlertServiceInterface $alertService, string $token): Response
    {
        if (!$user = $userRepository->findOneBy(['resetToken' => $token])) {
            $alertService->danger('Ce token n\'est pas valide');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $user->setResetToken(null);
            $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
            $entityManager->persist($user);
            $entityManager->flush();
            $alertService->success('Votre mot de passe a bien été réinitialisé');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/resetPassword.html.twig', ['token' => $token]);
    }
}
