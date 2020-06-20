<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchingType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\RegistrationFormType;
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
    public function index(
        PaginatorInterface $paginator,
        UserRepository $userRepository,
        Request $request
    ): Response {

        $form = $this->createForm(SearchingType::class);
        $form->handleRequest($request);

        $users = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            8
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $searches = $form['search']->getData();
            if ($searches !== null) {
                $users = $paginator->paginate(
                    $userRepository->findBySearch($searches),
                    $request->query->getInt('page', 1),
                    8
                );
            }
        }

        return $this->render('trombinoscope/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{login}", name="user_show")
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {

        return $this->render('ambassador/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/modification/{login}", name="user_edit")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, Request $request): Response
    {

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('ambassador/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'registrationForm' => $form->createView(),
        ]);
    }
}
