<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\HorseSchleichRepository;
use App\Repository\PetshopRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class UserController extends AbstractController
{

    /**
     * @Route("/profil/{name}", name="profile")
     * @param User $user
     * @param PetshopRepository $petshopRepository
     * @param HorseSchleichRepository $horseSchleichRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function profile(User $user,
                            PetshopRepository       $petshopRepository,
                            HorseSchleichRepository $horseSchleichRepository,
                            PaginatorInterface      $paginator,
                            Request                 $request
                            ): Response
    {

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
     * @param User $user
     * @param AlertServiceInterface $alertService
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function editProfile(User $user,
                                AlertServiceInterface    $alertService,
                                EntityManagerInterface      $entityManager,
                                Request                     $request):Response{
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
     * @param User $user
     * @param AlertServiceInterface $alertService
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function deleteImage(User $user,
                                AlertServiceInterface $alertService,
                                EntityManagerInterface $entityManager,
                                Request $request):Response{
        $this->denyAccessUnlessGranted('edit', $user);

        if (!$this->isCsrfTokenValid('delete-image'. $user->getId(), $request->request->get('token'))){
            throw new InvalidCsrfTokenException();
        }
        $user->setImageName(null);
        $entityManager->persist($user);
        $entityManager->flush();
        $alertService->success('Image supprimée avec succès');

        return $this->redirectToRoute('editProfile', [
            'name' => $user->getName()
        ]);
    }




}

