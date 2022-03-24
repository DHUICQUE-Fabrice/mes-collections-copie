<?php

namespace App\Entity;

use App\Repository\PetshopRepository;
use App\Traits\SluggableTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PetshopRepository::class)
 * @Vich\Uploadable
 */
class Petshop extends AbstractImageFile
{
    use SluggableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private ?string $biography;

    /**
     * @ORM\ManyToOne(targetEntity=PetshopSize::class, inversedBy="petshops")
     * @ORM\JoinColumn(nullable=false)
     * @var PetshopSize
     */
    private PetshopSize $size;

    /**
     * @ORM\ManyToOne(targetEntity=PetshopSpecies::class, inversedBy="petshops")
     * @ORM\JoinColumn(nullable=false)
     * @var PetshopSpecies
     */
    private PetshopSpecies $species;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="petshops")
     * @ORM\JoinColumn(nullable=false)
     * @var User
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=ObjectFamily::class, inversedBy="petshop")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="code", name="object_family_code")
     */
    private ?ObjectFamily $objectFamily = null;

    /**
     * @Vich\UploadableField(mapping="uploaded_images", fileNameProperty="imageName")
     * @var File|null
     */
    protected ?File $file = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
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
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }

    /**
     * @param $biography
     * @return $this
     */
    public function setBiography($biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * @return PetshopSize
     */
    public function getSize(): PetshopSize
    {
        return $this->size;
    }

    /**
     * @param $size
     * @return $this
     */
    public function setSize($size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return PetshopSpecies
     */
    public function getSpecies(): PetshopSpecies
    {
        return $this->species;
    }

    /**
     * @param $species
     * @return $this
     */
    public function setSpecies($species): self
    {
        $this->species = $species;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return ObjectFamily
     */
    public function getObjectFamily(): ObjectFamily
    {
        return $this->objectFamily;
    }

    /**
     * @param ObjectFamily|null $objectFamily
     * @return $this
     */
    public function setObjectFamily(?ObjectFamily $objectFamily): self
    {
        $this->objectFamily = $objectFamily;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
