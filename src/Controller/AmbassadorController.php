<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AmbassadorController
 * @package App\Controller
 * @Route("/ambassadeur", name="ambassador_")
 */
class AmbassadorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('ambassador/index.html.twig', [
            'controller_name' => 'AmbassadorController',
        ]);
    }
}
