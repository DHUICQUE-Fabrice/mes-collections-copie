<?php

namespace App\Entity;

use App\Repository\ObjectFamilyRepository;
use App\Traits\ReferentialTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=ObjectFamilyRepository::class)
 */
class ObjectFamily
{
    public const CODE_PETSHOP = 'PET';
    public const CODE_HORSE_SCHLEICH = 'HOR';

    use ReferentialTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     * @var string|null
     */
    private ?string $code = null;

    /**
     * @ORM\OneToMany(targetEntity=Petshop::class, mappedBy="objectFamily")
     */
    private Collection $petshop;

    /**
     * @ORM\OneToMany(targetEntity=HorseSchleich::class, mappedBy="objectFamily")
     */
    private Collection $horseSchleich;

    public function __construct()
    {
        $this->petshop = new ArrayCollection();
        $this->horseSchleich = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPetshop(): Collection
    {
        return $this->petshop;
    }

    /**
     * @param $petshop
     * @return $this
     */
    public function addPetshop($petshop): ObjectFamily
    {
        if (!$this->petshop->contains($petshop)) {
            $this->petshop[] = $petshop;
            $petshop->setObjectFamily($this);
        }
        return $this;
    }

    /**
     * @param $petshop
     * @return $this
     */
    public function removePetshop($petshop): ObjectFamily
    {
        if ($this->petshop->removeElement($petshop) && $petshop->getObjectFamily() === $this) {
            $petshop->setObjectFamily(null);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getHorseSchleich(): Collection
    {
        return $this->horseSchleich;
    }

    /**
     * @param $horseSchleich
     * @return $this
     */
    public function addHorseSchleich($horseSchleich): ObjectFamily
    {
        if (!$this->horseSchleich->contains($horseSchleich)) {
            $this->horseSchleich[] = $horseSchleich;
            $horseSchleich->setObjectFamily($this);
        }

        return $this;
    }

    /**
     * @param $horseSchleich
     * @return $this
     */
    public function removeHorseSchleich($horseSchleich): ObjectFamily
    {
        if ($this->horseSchleich->removeElement($horseSchleich) && $horseSchleich->getObjectFamily() === $this) {
            $horseSchleich->setObjectFamily(null);
        }

        return $this;
    }

}
