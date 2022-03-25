<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\HorseSchleichRepository;
use App\Repository\PetshopRepository;
use App\Repository\UserRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/profil/{name}", name="profile")
     * @param string $name
     * @param UserRepository $userRepository
     * @param PetshopRepository $petshopRepository
     * @param HorseSchleichRepository $horseSchleichRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function profile(string                  $name,
                            UserRepository          $userRepository,
                            PetshopRepository       $petshopRepository,
                            HorseSchleichRepository $horseSchleichRepository,
                            PaginatorInterface      $paginator,
                            Request                 $request
                            ): Response
    {

        $user = $userRepository->findOneBy(['name' => $name]);
        $petshops = $petshopRepository->findBy(['user' => $user]);
        $horseSchleiches = $horseSchleichRepository->findBy(['user' => $user]);

        $rawItems = array_merge($petshops, $horseSchleiches);
        $items = $paginator->paginate(
            $rawItems,
            $request->query->getInt('page', 1), 12
        );

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'items' => $items,
        ]);
    }


    /**
     * @Route("/profil/{name}/modifier", name="editProfile")
     * @param string $name
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editProfile(string                      $name,
                                AlertServiceInterface    $alertService,
                                UserRepository              $userRepository,
                                EntityManagerInterface      $entityManager,
                                Request                     $request){
        $user = $userRepository->findOneBy(['name' => $name]);
        $this->denyAccessUnlessGranted('edit', $user);
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()){
            $entityManager->persist($user);
            $alertService->success('Profil modifié avec succès');
            $entityManager->flush();
            return $this->redirectToRoute('profile', [
                'name' => $user->getName()
            ]);
        }
        return $this->render('user/edit.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/profil/{name}/supprimer", name="delete_profile_image")
     * @param string $name
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function deleteImage(string $name,
                                UserRepository $userRepository,
                                AlertServiceInterface $alertService,
                                EntityManagerInterface $entityManager,
                                Request $request){
        $user = $userRepository->findOneBy(['name' => $name]);
        $this->denyAccessUnlessGranted('edit', $user);
        // TODO: Display a confirmation message in a popup


        $user->setImageName(null);
        $entityManager->persist($user);
        $alertService->success('L\'image a bien été supprimée');
        $entityManager->flush();
        return $this->redirectToRoute('editProfile', [
            'name' => $user->getName()
        ]);
    }




}

