<?php

namespace App\Controller;

use Minishlink\WebPush\VAPID;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternalController extends AbstractController
{

    private VAPID $VAPID;



    /**
     * @Route("/internal", name="app_internal")
     */
    public function index(): Response
    {

        dd(VAPID::createVapidKeys()); // store the keys afterwards
        return $this->render('internal/index.html.twig', [
            'controller_name' => 'InternalController',
        ]);
    }
}
