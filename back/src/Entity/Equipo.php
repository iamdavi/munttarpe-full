<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipoRepository::class)]
class Equipo
{

    const MAPPED_PROPERTIES_METHODS = [
        'nombre' => 'setNombre',
        'color' => 'setColor',
        'genero' => 'setGenero',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $genero = null;

    /**
     * @var Collection<int, Jugador>
     */
    #[ORM\OneToMany(targetEntity: Jugador::class, mappedBy: 'equipo', orphanRemoval: true)]
    private Collection $jugadores;

    /**
     * @var Collection<int, Concepto>
     */
    #[ORM\OneToMany(targetEntity: Concepto::class, mappedBy: 'equipo', orphanRemoval: true)]
    private Collection $conceptos;

    public function __construct()
    {
        $this->jugadores = new ArrayCollection();
        $this->conceptos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * @return Collection<int, Jugador>
     */
    public function getJugadores(): Collection
    {
        return $this->jugadores;
    }

    public function addJugadore(Jugador $jugadore): static
    {
        if (!$this->jugadores->contains($jugadore)) {
            $this->jugadores->add($jugadore);
            $jugadore->setEquipo($this);
        }

        return $this;
    }

    public function removeJugadore(Jugador $jugadore): static
    {
        if ($this->jugadores->removeElement($jugadore)) {
            // set the owning side to null (unless already changed)
            if ($jugadore->getEquipo() === $this) {
                $jugadore->setEquipo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Concepto>
     */
    public function getConceptos(): Collection
    {
        return $this->conceptos;
    }

    public function addConcepto(Concepto $concepto): static
    {
        if (!$this->conceptos->contains($concepto)) {
            $this->conceptos->add($concepto);
            $concepto->setEquipo($this);
        }

        return $this;
    }

    public function removeConcepto(Concepto $concepto): static
    {
        if ($this->conceptos->removeElement($concepto)) {
            // set the owning side to null (unless already changed)
            if ($concepto->getEquipo() === $this) {
                $concepto->setEquipo(null);
            }
        }

        return $this;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'color' => $this->getColor(),
            'genero' => $this->getGenero()
        ];
    }
}
