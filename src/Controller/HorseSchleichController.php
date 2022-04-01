<?php

namespace App\Controller;

use App\Entity\HorseSchleich;
use App\Entity\ObjectFamily;
use App\Entity\User;
use App\Form\HorseSchleichType;
use App\Repository\HorseSchleichRepository;
use App\Service\AlertServiceInterface;
use App\Service\PaginatorService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class HorseSchleichController extends AbstractController
{
    /**
     * @var HorseSchleichRepository
     */
    private HorseSchleichRepository $repository;

    /**
     * @param HorseSchleichRepository $horseSchleichRepository
     */
    public function __construct(HorseSchleichRepository $horseSchleichRepository)
    {
        $this->repository = $horseSchleichRepository;
    }

    /**
     * @Route("/chevaux-schleich", name="horse_schleich")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $paginatorSvc = new PaginatorService();
        $horseSchleiches = $paginatorSvc->paginate($this->repository, $paginator, $request);
        return $this->render('horse_schleich/all.html.twig', [
            'horseSchleiches' => $horseSchleiches,
        ]);
    }

    /**
     * @Route ("/schleich/details/{slug}", name="horse_schleich_details", requirements={"slug": "[a-z0-9\-]*"})
     * @param HorseSchleich $horseSchleich
     * @return Response
     */
    public function show(HorseSchleich $horseSchleich): Response
    {
        return $this->render('horse_schleich/details.html.twig', [
            'schleich' => $horseSchleich
       ]);
    }


    /**
     * @Route("/nouveau/cheval-schleich", name="create_new_horseSchleich")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param AlertServiceInterface $alertService
     * @return Response
     * @throws ORMException
     */
    public function createNewHorseSchleich(EntityManagerInterface $entityManager,
                                           Request                $request,
                                           AlertServiceInterface $alertService
    ): Response
    {
        $horseSchleich = new HorseSchleich();
        $horseSchleichForm = $this->createForm(HorseSchleichType::class, $horseSchleich);

        $horseSchleichForm->handleRequest($request);

        if ($horseSchleichForm->isSubmitted() && $horseSchleichForm->isValid()){
            /** @var $user User */
            $user = $this->getUser();

            $horseSchleich->setUser($user);

            $family = $entityManager->getReference(ObjectFamily::class, ObjectFamily::CODE_HORSE_SCHLEICH);
            $horseSchleich->setObjectFamily($family);

            $entityManager->persist($horseSchleich);
            $entityManager->flush();

            $alertService->success(sprintf('<b>%s</b> a bien été ajouté !', $horseSchleich->getName()));

            return $this->redirectToRoute('horse_schleich_details', ['slug'=>$horseSchleich->getSlug()]);
        }
        return $this->render('create/horseschleich.html.twig', [
            'horseSchleichForm'=>$horseSchleichForm->createView()
        ]);
    }

    /**
     * @Route ("/modifier/schleich/{id}", name="edit_horseschleich")
     * @param HorseSchleich $horseSchleich
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function editHorseSchleich(HorseSchleich $horseSchleich,
                                      Request $request,
                                      EntityManagerInterface  $entityManager):Response{
        $this->denyAccessUnlessGranted('edit', $horseSchleich);
        $horseSchleichForm = $this->createForm(HorseSchleichType::class, $horseSchleich);
        $horseSchleichForm->handleRequest($request);
        if ($horseSchleichForm->isSubmitted() && $horseSchleichForm->isValid()){
            $entityManager->persist($horseSchleich);
            $entityManager->flush();
            return $this->redirectToRoute('horse_schleich_details', ['slug'=>$horseSchleich->getSlug()]);
        }
        return $this->render('horse_schleich/edit.html.twig',[
            'horseSchleichForm' => $horseSchleichForm->createView(),
            'horseSchleich' => $horseSchleich,
        ]);
    }

    /**
     * @Route ("/supprimer/schleich/{id}", name="delete_horse_schleich")
     * @param HorseSchleich $horseSchleich
     * @param AlertServiceInterface $alertService
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function delete(HorseSchleich $horseSchleich,
                           AlertServiceInterface $alertService,
                           EntityManagerInterface $entityManager,
                           Request $request): Response
    {
        /** @var $user User */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('delete', $horseSchleich);

        if (!$this->isCsrfTokenValid('delete-horse-schleich'.$horseSchleich->getId(), $request->request->get('token'))){
            throw new InvalidCsrfTokenException();
        }
        $entityManager->remove($horseSchleich);
        $alertService->success(sprintf('<b>%s</b> a bien été supprimé !', $horseSchleich->getName()));
        $entityManager->flush();

        return $this->redirectToRoute('profile', ['name' => $user->getName()]);
    }

    /**
     * @Route ("/schleich/{id}/delete/image", name="delete_horse_schleich_image")
     * @param HorseSchleich $horseSchleich
     * @param AlertServiceInterface $alertService
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function deleteImage(HorseSchleich $horseSchleich,
                                AlertServiceInterface $alertService,
                                EntityManagerInterface $entityManager,
                                Request $request): Response
    {
        $this->denyAccessUnlessGranted('edit', $horseSchleich);

        if (!$this->isCsrfTokenValid('delete-image'.$horseSchleich->getId(), $request->request->get('token'))){
            throw new InvalidCsrfTokenException();
        }
        $horseSchleich->setImageName(null);
        $entityManager->persist($horseSchleich);
        $alertService->success('L\'image a bien été supprimée !');
        $entityManager->flush();

        return $this->redirectToRoute('horse_schleich_details', ['slug' => $horseSchleich->getSlug()]);
    }
}
