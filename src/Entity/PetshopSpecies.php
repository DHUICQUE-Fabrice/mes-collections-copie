<?php

namespace App\Entity;

use App\Repository\PetshopSpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=PetshopSpeciesRepository::class)
 */
class PetshopSpecies
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=Petshop::class, mappedBy="species")
     */
    private Collection $petshops;

    public function __construct()
    {
        $this->petshops = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name): PetshopSpecies
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPetshops(): Collection
    {
        return $this->petshops;
    }

    /**
     * @param $petshop
     * @return $this
     */
    public function addPetshop($petshop): PetshopSpecies
    {
        if (!$this->petshops->contains($petshop)) {
            $this->petshops[] = $petshop;
            $petshop->setSpecies($this);
        }

        return $this;
    }

    /**
     * @param $petshop
     * @return $this
     */
    public function removePetshop($petshop): PetshopSpecies
    {
        if ($this->petshops->removeElement($petshop) && $petshop->getSpecies() === $this) {
            $petshop->setSpecies(null);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

}
