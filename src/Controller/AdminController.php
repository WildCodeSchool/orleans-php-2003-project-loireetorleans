<?php


namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @route("/admin", name="admin_index")
     * @return Response
     * @IsGranted("ROLE_ADMINISTRATEUR")
     */
    public function home(): Response
    {
        return $this->render('admin/home.html.twig');
    }
}
