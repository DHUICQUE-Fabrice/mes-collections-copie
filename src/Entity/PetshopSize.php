<?php

namespace App\Entity;

use App\Repository\PetshopSizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=PetshopSizeRepository::class)
 */
class PetshopSize
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
     * @ORM\OneToMany(targetEntity=Petshop::class, mappedBy="size")
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
    public function setName($name): PetshopSize
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPetshops(): Collection
    {
        return $this->petshops;
    }

    /**
     * @param $petshop
     * @return $this
     */
    public function addPetshop($petshop): PetshopSize
    {
        if (!$this->petshops->contains($petshop)) {
            $this->petshops[] = $petshop;
            $petshop->setSize($this);
        }
        return $this;
    }

    /**
     * @param Petshop $petshop
     * @return $this
     */
    public function removePetshop(Petshop $petshop): PetshopSize
    {
        if ($this->petshops->removeElement($petshop) && $petshop->getSize() === $this) {
            $petshop->setSize(null);
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
