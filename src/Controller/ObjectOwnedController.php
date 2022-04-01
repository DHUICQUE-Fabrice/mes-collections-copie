<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjectOwnedController extends AbstractController
{
    /**
     * @Route("/nouveau", name="create_new")
     * @return Response
     */
    public function chooseWhatToCreate(): Response
    {
        return $this->render('create/index.html.twig');
    }
}
