<?php

namespace App\Entity;

use App\Repository\ConceptoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConceptoRepository::class)]
class Concepto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $texto = null;

    #[ORM\Column]
    private ?float $valor = null;

    #[ORM\ManyToOne(inversedBy: 'conceptos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?equipo $equipo = null;

    /**
     * @var Collection<int, Multa>
     */
    #[ORM\OneToMany(targetEntity: Multa::class, mappedBy: 'concepto', orphanRemoval: true)]
    private Collection $multas;

    public function __construct()
    {
        $this->multas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): static
    {
        $this->texto = $texto;

        return $this;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): static
    {
        $this->valor = $valor;

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
            $multa->setConcepto($this);
        }

        return $this;
    }

    public function removeMulta(Multa $multa): static
    {
        if ($this->multas->removeElement($multa)) {
            // set the owning side to null (unless already changed)
            if ($multa->getConcepto() === $this) {
                $multa->setConcepto(null);
            }
        }

        return $this;
    }
}
