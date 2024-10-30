<?php

namespace App\Entity;

use App\Repository\MultaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MultaRepository::class)]
class Multa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'multas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?jugador $jugador = null;

    #[ORM\ManyToOne(inversedBy: 'multas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?concepto $concepto = null;

    #[ORM\Column]
    private ?float $precio = null;

    #[ORM\Column]
    private ?bool $pagada = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaPagada = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJugador(): ?jugador
    {
        return $this->jugador;
    }

    public function setJugador(?jugador $jugador): static
    {
        $this->jugador = $jugador;

        return $this;
    }

    public function getConcepto(): ?concepto
    {
        return $this->concepto;
    }

    public function setConcepto(?concepto $concepto): static
    {
        $this->concepto = $concepto;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function isPagada(): ?bool
    {
        return $this->pagada;
    }

    public function setPagada(bool $pagada): static
    {
        $this->pagada = $pagada;

        return $this;
    }

    public function getFechaPagada(): ?\DateTimeInterface
    {
        return $this->fechaPagada;
    }

    public function setFechaPagada(?\DateTimeInterface $fechaPagada): static
    {
        $this->fechaPagada = $fechaPagada;

        return $this;
    }
}
