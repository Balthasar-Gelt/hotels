<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CheckoutType;
use App\Service\BookingService;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/booking')]
final class BookingController extends AbstractController
{
    #[Route('/book_hotel', name: 'book_hotel', methods: ['POST'])]
    public function bookHotel(Request $request, BookingRepository $bookingRepository, HotelRepository $hotelRepository, BookingService $bookingService): Response
    {
        $hotelId = $request->request->get('hotel_id');
        $selectedDates = explode(',', $request->request->get('selected_dates'));
        $dates = array_map(function ($date) {
            return new \DateTime($date);
        }, $selectedDates);

        sort($dates);

        $overlappingBookings = $bookingRepository->findOverlappingBookings($dates[0], end($dates), $hotelId);

        if (!empty($overlappingBookings)) {
            $this->addFlash('error', 'The selected dates are not available. Please select dates that are not booked for the hotel.');
            return $this->redirectToRoute('hotel_show', ['id' => $hotelId]);
        }

        $bookingService->storeBookingData([
            'hotel_data' => $hotelRepository->find($hotelId),
            'dates' => $dates,
        ]);


        return $this->redirectToRoute('checkout');
    }

    #[Route('/checkout', name: 'checkout', methods: ['GET', 'POST'])]
    public function checkout(Request $request, EntityManagerInterface $entityManager, BookingService $bookingService): Response
    {
        $bookingData = $bookingService->getBookingData();

        if (!$bookingData) {
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(CheckoutType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bookingService->createBooking($bookingData, $form, $this->getUser()));
            $entityManager->flush();

            return $this->redirectToRoute('confirmation');
        }

        $bookingData['booking_total'] = $bookingService->getFormatedTotalPrice(
            $bookingData['dates'],
            $bookingData['hotel_data']->getPricePerNight()
        );

        $bookingService->storeBookingData($bookingData);

        return $this->render('booking/checkout.html.twig', [
            'form' => $form->createView(),
            'booking_data' => $bookingData
        ]);
    }

    #[Route('/confirmation', name: 'confirmation', methods: ['GET'])]
    public function confirmation(BookingService $bookingService): Response
    {
        $bookingData = $bookingService->getBookingData();

        if (!$bookingData) {
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        $bookingService->clearBookingData();

        return $this->render('booking/confirmation.html.twig', [
            'booking_data' => $bookingData,
            'booking_total' => $bookingData['booking_total'],
        ]);
    }
}
