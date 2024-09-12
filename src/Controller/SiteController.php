<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SiteController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('site/index.html.twig', [
            'featured_hotels' => $hotelRepository->findBy([], null, 2),
            'special_offers' => null
        ]);
    }

    #[Route('/contact', name: 'contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig');
    }
}
