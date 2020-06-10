<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrombiController extends AbstractController
{
    /**
     * @Route("/trombi", name="trombi_index")
     * @return Response
     *
     */
    public function index(): Response
    {
        return $this->render('Trombinoscope/index.html.twig');
    }
}
