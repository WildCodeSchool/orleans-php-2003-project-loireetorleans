<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/trombinoscope", name="user_index")
     * @param PaginatorInterface $paginator
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, UserRepository $userRepository, Request $request): Response
    {
        return $this->render('trombinoscope/index.html.twig', [
            'users' => $paginator->paginate(
                $userRepository->findAll(),
                $request->query->getInt('page', 5),
                8
            )
        ]);
    }

    /**
     * @Route("/{login}", name="user_show")
     */
    public function show($login): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['login' => $login]);

        return $this->render('ambassador/show.html.twig', [
            'user' => $user,
        ]);
    }
}
