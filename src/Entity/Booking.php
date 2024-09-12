<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $hotel_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $user_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $booked_from = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $booked_until = null;

    #[ORM\Column]
    private ?int $total_price = null;

    #[ORM\Column(type: 'json')]
    private ?array $billingInformation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    function __construct()
    {
        $this->created_at = $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotelId(): ?int
    {
        return $this->hotel_id;
    }

    public function setHotelId(int $hotel_id): static
    {
        $this->hotel_id = $hotel_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBookedFrom(): ?\DateTimeInterface
    {
        return $this->booked_from;
    }

    public function setBookedFrom(\DateTimeInterface $booked_from): static
    {
        $this->booked_from = $booked_from;

        return $this;
    }

    public function getBookedUntil(): ?\DateTimeInterface
    {
        return $this->booked_until;
    }

    public function setBookedUntil(\DateTimeInterface $booked_until): static
    {
        $this->booked_until = $booked_until;

        return $this;
    }

    public function getFormattedTotalPrice(): string
    {
        return number_format($this->total_price / 100, 0, '.', ' ');
    }

    public function getTotalPrice(): ?int
    {
        return $this->total_price;
    }

    public function setTotalPrice(int $total_price): static
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getBillingInformation(): ?array
    {
        return $this->billingInformation;
    }

    public function setBillingInformation(array $billingInformation): self
    {
        $this->billingInformation = $billingInformation;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
