<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\HotelRepository;
use App\Service\BookingService;

class HotelController extends AbstractController
{
    #[Route('/hotel', name: 'hotel_index', methods: ['GET'])]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll()
        ]);
    }

    #[Route('/hotel/{id}', name: 'hotel_show', methods: ['GET'])]
    public function show(BookingService $bookingService, HotelRepository $hotelRepository, $id): Response
    {
        $hotel = $hotelRepository->findOrFail($id);
        $bookedDates = $bookingService->getBookedDatesForHotel($hotel->getId());

        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
            'booked_dates' => $bookedDates,
        ]);
    }
}
