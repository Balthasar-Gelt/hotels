<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\User;
use App\Repository\BookingRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingService
{
    private SessionInterface $session;

    public function __construct(private BookingRepository $bookingRepository, RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    /**
     * Creates a Booking entity based on the provided booking data, form input, and optionally the user.
     *
     * @param array $bookingData An array containing booking-related data such as hotel information and dates.
     * @param FormInterface $checkoutForm The checkout form containing user input like billing information.
     * @param User|null $user (Optional) The user who is making the booking. Null if it's a guest booking.
     *
     * @return Booking The newly created Booking entity.
     */
    public function createBooking(array $bookingData, FormInterface $checkoutForm, ?User $user = null): Booking
    {
        $booking = new Booking();

        $booking->setHotelId($bookingData['hotel_data']->getId());

        if ($user) {
            $booking->setUserId($user->getId());
        }

        $booking->setBookedFrom($bookingData['dates'][0]);
        $booking->setBookedUntil(end($bookingData['dates']));
        $booking->setTotalPrice($this->calculateTotalPrice($bookingData['dates'], $bookingData['hotel_data']->getPricePerNight()));
        $booking->setBillingInformation($checkoutForm->getData());

        return $booking;
    }

    /**
     * Calculates the total price of the booking based on the number of nights and the price per night.
     *
     * @param array $bookingDates An array of dates representing the booked nights.
     * @param int $hotelPricePerNight The price per night for the hotel.
     *
     * @return int The total price of the booking in cents.
     */
    public function calculateTotalPrice(array $bookingDates, int $hotelPricePerNight): int
    {
        $nights = count($bookingDates);
        return $hotelPricePerNight * $nights;
    }

    /**
     * Formats a given price by dividing it by 100 and applying thousand separators.
     *
     * @param int $price The price in cents to be formatted.
     *
     * @return string The formatted price string (e.g., "1 000").
     */
    public function formatPrice(int $price): string
    {
        return number_format($price / 100, 0, '.', ' ');
    }

    /**
     * Combines the total price calculation and formatting into one step.
     *
     * @param array $bookingDates An array of dates representing the booked nights.
     * @param int $hotelPricePerNight The price per night for the hotel.
     *
     * @return string The formatted total price string (e.g., "1 000").
     */
    public function getFormatedTotalPrice(array $bookingDates, int $hotelPricePerNight): string
    {
        return $this->formatPrice($this->calculateTotalPrice($bookingDates, $hotelPricePerNight));
    }

    /**
     * Retrieves booked date ranges for a given hotel based on its ID.
     *
     * @param int $hotelId The ID of the hotel.
     *
     * @return array An array of booked date ranges as strings in the format "YYYY-MM-DD:YYYY-MM-DD".
     */
    public function getBookedDatesForHotel(int $hotelId): array
    {
        $bookings = $this->bookingRepository->findBy(['hotel_id' => $hotelId]);
        $bookedDates = [];

        foreach ($bookings as $booking) {
            $bookedDates[] = $booking->getBookedFrom()->format('Y-m-d') . ':' . $booking->getBookedUntil()->format('Y-m-d');
        }

        return $bookedDates;
    }

    /**
     * Stores booking data in the session.
     *
     * @param array $bookingData The booking data to be stored in the session.
     */
    public function storeBookingData(array $bookingData): void
    {
        $this->session->set('booking_data', $bookingData);
    }

    /**
     * Retrieves the booking data from the session.
     *
     * @return array|null The booking data from the session, or null if none exists.
     */
    public function getBookingData(): ?array
    {
        return $this->session->get('booking_data', null);
    }

    /**
     * Clears the booking data from the session.
     */
    public function clearBookingData(): void
    {
        $this->session->remove('booking_data');
    }
}
