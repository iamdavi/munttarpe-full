<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{

    const MAPPED_PROPERTIES_METHODS = [
        'tipo' => 'setTipo',
        'descripcion' => 'setDescripcion',
        'recurrente' => 'setRecurrente',
        'fecha' => 'setFecha',
        'hora' => 'setHora',
        'dias' => 'setDias',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?bool $recurrente = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hora = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $dias = null;

    /**
     * @var Collection<int, Equipo>
     */
    #[ORM\ManyToMany(targetEntity: Equipo::class, inversedBy: 'eventos', fetch: 'EAGER')]
    private Collection $equipos;

    public function __construct()
    {
        $this->equipos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

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

    public function isRecurrente(): ?bool
    {
        return $this->recurrente;
    }

    public function setRecurrente(bool $recurrente): static
    {
        $this->recurrente = $recurrente;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): static
    {
        $this->hora = $hora;

        return $this;
    }

    public function getDias(): ?array
    {
        return $this->dias;
    }

    public function setDias(?array $dias): static
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * @return Collection<int, equipo>
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(equipo $equipo): static
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos->add($equipo);
        }

        return $this;
    }

    public function removeEquipo(equipo $equipo): static
    {
        $this->equipos->removeElement($equipo);

        return $this;
    }

    public function serialize()
    {
        return [
            'id' => $this->getId(),
            'tipo' => $this->getTipo(),
            'descripcion' => $this->getDescripcion(),
            'recurrente' => $this->isRecurrente(),
            'fecha' => $this->getFecha()->format('m/d/Y'),
            'hora' => $this->getHora()->format('H:m'),
            'dias' => $this->getDias(),
            'equipos' => $this->getEquipos()->map(fn($equipo) => $equipo->serialize())->toArray()
        ];
    }
}
