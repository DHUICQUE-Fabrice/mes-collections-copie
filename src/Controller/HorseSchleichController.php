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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HorseSchleichController extends AbstractController
{
    /**
     * @var HorseSchleichRepository
     */
    private $repository;

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
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param HorseSchleichRepository $horseSchleichRepository
     * @return RedirectResponse|Response
     */
    public function editHorseSchleich(int                     $id, Request $request,
                                      EntityManagerInterface  $entityManager,
                                      HorseSchleichRepository $horseSchleichRepository){
        $horseSchleich = $horseSchleichRepository->find($id);

        $this->denyAccessUnlessGranted('edit', $horseSchleich);

        $horseSchleichForm = $this->createForm(HorseSchleichType::class, $horseSchleich);
        $horseSchleichForm->handleRequest($request);
        if ($horseSchleichForm->isSubmitted() && $horseSchleichForm->isValid()){
            $entityManager->persist($horseSchleich);
            $entityManager->flush();
            return $this->redirectToRoute('horse_schleich_details', ['id'=>$id, 'slug'=>$horseSchleich->getSlug()]);
        }
        return $this->render('horse_schleich/edit.html.twig',[
            'horseSchleichForm' => $horseSchleichForm->createView()
        ]);
    }

    /**
     * @Route ("/supprimer/schleich/{id}", name="delete-horse_schleich")
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param HorseSchleichRepository $horseSchleichRepository
     * @return Response
     */
    public function delete(int $id, EntityManagerInterface $entityManager, HorseSchleichRepository $horseSchleichRepository): Response
    {
        $horseSchleich = $horseSchleichRepository->find($id);
        /** @var $user User */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('delete', $horseSchleich);
        $entityManager->remove($horseSchleich);
        $entityManager->flush();
        return $this->redirectToRoute('profile', ['nickname' => $user->getNickName()]);
    }
}
