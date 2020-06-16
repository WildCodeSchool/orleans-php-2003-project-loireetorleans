<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gestion/profil", name="admin_profile")
 */
class AdminProfileController extends AbstractController
{
    /**
     * @Route("/", name="_index")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository) : Response
    {
        $users = $userRepository->findAllWhithoutAdmin();
        return $this->render('admin_profile/index.html.twig', [
            'users' => $users,
        ]);
    }
}
