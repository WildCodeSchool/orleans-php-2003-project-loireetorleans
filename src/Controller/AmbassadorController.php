<?php

namespace App\Controller;

use App\Entity\User;
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
     * @Route("/{login}", name="index")
     */
    public function show($login): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['login' => $login]);

        return $this->render('ambassador/profil.html.twig', [
            'controller_name' => 'AmbassadorController',
            'user' => $user,
        ]);
    }
}
