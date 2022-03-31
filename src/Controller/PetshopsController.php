<?php

namespace App\Controller;

use App\Entity\ObjectFamily;
use App\Entity\Petshop;
use App\Entity\User;
use App\Form\PetshopType;
use App\Repository\PetshopRepository;
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

class PetshopsController extends AbstractController
{
    /**
     * @var PetshopRepository
     */
    private PetshopRepository $repository;


    /**
     * @param PetshopRepository $petshopRepository
     */
    public function __construct(PetshopRepository $petshopRepository)
    {
        $this->repository = $petshopRepository;
    }

    /**
     * @Route("/petshops", name="petshops")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $paginatorSvc = new PaginatorService();
        $petshops = $paginatorSvc->paginate($this->repository, $paginator,$request);

        return $this->render('petshops/all.html.twig', [
            'petshops' => $petshops,
        ]);
    }

    /**
     * @Route ("/petshop/details/{slug}", name="petshop_details", requirements={"slug": "[a-z0-9\-]*"})
     * @param Petshop $petshop
     * @return Response
     */
    public function show(Petshop $petshop): Response
    {
        return $this->render('petshops/details.html.twig', [
            'petshop' => $petshop,
        ]);
    }


    /**
     * @Route("/nouveau/petshop", name="create_new_petshop")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param AlertServiceInterface $alertService
     * @return Response
     * @throws ORMException
     */
    public function new(EntityManagerInterface $entityManager,
                        Request $request,
                        AlertServiceInterface $alertService): Response
    {
        $petshop = new Petshop();

        $form = $this->createForm(PetshopType::class, $petshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $user User */
            $user = $this->getUser();

            $petshop->setUser($user);

            $family = $entityManager->getReference(ObjectFamily::class, ObjectFamily::CODE_PETSHOP);
            $petshop->setObjectFamily($family);

            $entityManager->persist($petshop);
            $entityManager->flush();

            $alertService->success(sprintf('<b>%s</b> a bien été ajouté !', $petshop->getName()));

            return $this->redirectToRoute('petshop_details', ['slug'=>$petshop->getSlug()]);
        }

        return $this->render('create/petshop.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route ("/modifier/petshop/{id}", name="edit_petshop")
     * @param Petshop $petshop
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @return Response
     */
    public function edit(Petshop $petshop,
                         Request $request,
                         EntityManagerInterface $entityManager,
                         AlertServiceInterface   $alertService): Response{

        $this->denyAccessUnlessGranted('edit', $petshop);

        $petshopForm = $this->createForm(PetshopType::class, $petshop);
        $petshopForm->handleRequest($request);

        if ($petshopForm->isSubmitted() && $petshopForm->isValid()){
            $entityManager->persist($petshop);
            $entityManager->flush();
            $alertService->success(sprintf('<b>%s</b> a bien été modifié !', $petshop->getName()));

            return $this->redirectToRoute('petshop_details', ['slug'=>$petshop->getSlug()]);
        }

        return $this->render('petshops/edit.html.twig',[
            'petshopForm' => $petshopForm->createView(),
            'petshop' => $petshop,
        ]);
    }

    /**
     * @Route ("/supprimer/petshop/{id}", name="delete_petshop")
     * @param Petshop $petshop
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param AlertServiceInterface $alertService
     * @return Response
     */
    public function delete(Petshop $petshop,
                          EntityManagerInterface $entityManager,
                          Request $request,
                          AlertServiceInterface $alertService): Response
    {
        /** @var $user User */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('delete', $petshop);
        $name = $petshop->getName();

        if (!$this->isCsrfTokenValid('delete-petshop'.$petshop->getId(), $request->request->get('token'))) {
            throw new InvalidCsrfTokenException();
        }
        $entityManager->remove($petshop);
        $entityManager->flush();
        $alertService->success(sprintf('<b>%s</b> a bien été supprimé !', $name));
        return $this->redirectToRoute('profile', ['name' => $user->getName()]);
    }

    /**
     * @Route("/petshop/{id}/delete/image", name="delete_petshop_image")
     * @param Petshop $petshop
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param Request $request
     * @return Response
     */
    public function deleteImage(Petshop $petshop,
                                EntityManagerInterface $entityManager,
                                AlertServiceInterface $alertService,
                                Request $request): Response
    {
        $this->denyAccessUnlessGranted('edit', $petshop);

        if (!$this->isCsrfTokenValid('delete-image'.$petshop->getId(), $request->request->get('token'))) {
            throw new InvalidCsrfTokenException();
        }
            $petshop->setImageName(null);
            $entityManager->persist($petshop);
            $alertService->success('L\'image a bien été supprimée !');
            $entityManager->flush();

        return $this->redirectToRoute('petshop_details', ['slug' => $petshop->getSlug()]);
    }

}
