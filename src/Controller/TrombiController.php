<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrombiController extends AbstractController
{
    /**
     * @Route("/trombinoscope", name="trombi_index")
     * @return Response
     *
     */
    public function index(): Response
    {
        return $this->render('trombinoscope/index.html.twig');
    }
}
