<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     *
     * Gestion de l'affichage de tous les product
     *
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }
}
