<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */

class HomeController extends AbstractController
{
    /**
     *@Route("/", name="show_home")
     */
    public function show()
    {

        return $this->render("home/index.html.twig");
    }
}