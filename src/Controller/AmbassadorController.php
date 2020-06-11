<?php

namespace App\Controller;

use App\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/trombinoscope", name="card")
     * @return Response
     */
    public function show()
    {
        $cards = $this->getDoctrine()
            ->getRepository(Card::class)
            ->findAll();

        return $this->render('trombinoscope/index.html.twig', [
            'cards' => $cards,
        ]);
    }
}
