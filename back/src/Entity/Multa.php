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
    private ?Jugador $jugador = null;

    #[ORM\ManyToOne(inversedBy: 'multas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Concepto $concepto = null;

    #[ORM\Column]
    private ?float $precio = null;

    #[ORM\Column]
    private ?float $precioPagado = null;

    #[ORM\Column]
    private ?bool $pagada = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaPagada = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getPrecioPagado(): ?float
    {
        return $this->precioPagado;
    }

    public function setPrecioPagado(float $precioPagado): static
    {
        $this->precioPagado = $precioPagado;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function serialize()
    {
        return [
            'id' => $this->getId(),
            'concepto' => $this->getConcepto()->serialize(),
            'precio' => $this->getPrecio(),
            'precioPagado' => $this->getPrecioPagado(),
            'pagada' => $this->isPagada(),
            'descripcion' => $this->getDescripcion(),
            'fechaPagada' => $this->getFechaPagada(),
            'fecha' => $this->getFecha(),
        ];
    }
}
