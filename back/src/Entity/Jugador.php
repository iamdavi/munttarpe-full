<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorRepository::class)]
class Jugador
{

    const MAPPED_PROPERTIES_METHODS = [
        'nombre' => 'setNombre',
        'apellidos' => 'setApellidos',
        'mote' => 'setMote',
        'posicion' => 'setPosicion',
        'dorsal' => 'setDorsal',
        'rol' => 'setRol',
    ];

    const POSICION_OPTIONS = [
        'portero' => 'Portero',
        'central' => 'Central',
        'pivote' => 'Pivote',
        'latde' => 'Lateral derecho',
        'latiz' => 'Lateral izquierdo',
        'extde' => 'Extremo derecho',
        'extiz' => 'Extremo izquierdo',
    ];

    const ROL_OPTIONS = [
        'jugador' => 'Jugador',
        'entrenador' => 'Entrenador',
        'segentrenador' => '2º Entrenador',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 255)]
    private ?string $mote = null;

    #[ORM\Column(length: 255)]
    private ?string $posicion = null;

    #[ORM\Column(nullable: true)]
    private ?int $dorsal = null;

    #[ORM\Column(length: 255)]
    private ?string $rol = null;

    #[ORM\ManyToOne(inversedBy: 'jugadores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipo $equipo = null;

    /**
     * @var Collection<int, Multa>
     */
    #[ORM\OneToMany(targetEntity: Multa::class, mappedBy: 'jugador', orphanRemoval: true)]
    private Collection $multas;

    public function __construct()
    {
        $this->multas = new ArrayCollection();
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getMote(): ?string
    {
        return $this->mote;
    }

    public function setMote(string $mote): static
    {
        $this->mote = $mote;

        return $this;
    }

    public function getPosicion(): ?string
    {
        return $this->posicion;
    }

    public function setPosicion(string $posicion): static
    {
        $this->posicion = $posicion;

        return $this;
    }

    public function getDorsal(): ?int
    {
        return $this->dorsal;
    }

    public function setDorsal(?int $dorsal): static
    {
        $this->dorsal = $dorsal;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function getEquipo(): ?equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?equipo $equipo): static
    {
        $this->equipo = $equipo;

        return $this;
    }

    /**
     * @return Collection<int, Multa>
     */
    public function getMultas(): Collection
    {
        return $this->multas;
    }

    public function addMulta(Multa $multa): static
    {
        if (!$this->multas->contains($multa)) {
            $this->multas->add($multa);
            $multa->setJugador($this);
        }

        return $this;
    }

    public function removeMulta(Multa $multa): static
    {
        if ($this->multas->removeElement($multa)) {
            // set the owning side to null (unless already changed)
            if ($multa->getJugador() === $this) {
                $multa->setJugador(null);
            }
        }

        return $this;
    }

    public function serialize()
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'apellidos' => $this->getApellidos(),
            'mote' => $this->getMote(),
            'posicion' => $this->getPosicion(),
            'dorsal' => $this->getDorsal(),
            'rol' => $this->getRol(),
            'equipo' => $this->getEquipo()->serialize(),
            'multas' => $this->getMultas()->map(fn($m) => $m->serialize())->toArray()
        ];
    }
}
