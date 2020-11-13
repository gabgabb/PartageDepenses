<?php

namespace App\Entity;

use App\Repository\ParticipantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=ParticipantsRepository::class)
 */
class Participants
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $quotient;

    /**
     * @ORM\OneToMany(targetEntity=Depense::class, mappedBy="participants", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $depense;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evenement;

    public function __construct()
    {
        $this->depense = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|depense[]
     */
    public function getDepense(): Collection
    {
        return $this->depense;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function addDepense(depense $depense): self
    {

        if (!$this->depense->contains($depense)) {
            $this->depense[] = $depense;

            $depense->setParticipants($this);
        }

        return $this;
    }

    public function removeDepense(depense $depense): self
    {
        if ($this->depense->contains($depense)) {
            $this->depense->removeElement($depense);
            // set the owning side to null (unless already changed)
            if ($depense->getParticipants() === $this) {
                $depense->setParticipants(null);
            }
        }

        return $this;
    }


    public function getQuotient(): ?int
    {
        return $this->quotient;
    }

    public function setQuotient(int $quotient): self
    {
        $this->quotient = $quotient;

        return $this;
    }

    public function montantTot(): float
    {
        $mont = 0;
        for ($i = 0; $i < $this->depense->count(); $i++) {
            $mont += $this->getDepense()->get($i)->getMontant();
        }
        return $mont;
    }

    public function rapportQuotMont():float {
        $montTotParticipant=0;
        $quotientTot=0;

        for($i = 0; $i < $this->getEvenement()->getParticipants()->count(); $i++){
            $quotientTot+=$this->getEvenement()->getParticipants()->get($i)->getQuotient();
            $montTotParticipant+=$this->getEvenement()->getParticipants()->get($i)->montantTot();
        }
         return $montTotParticipant/$quotientTot;
    }
}
