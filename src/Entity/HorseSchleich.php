<?php

namespace App\Entity;

use App\Repository\SchleichRepository;
use App\Traits\SluggableTrait;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=SchleichRepository::class)
 * @Vich\Uploadable
 */
class HorseSchleich extends AbstractImageFile
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
     * @ORM\ManyToOne(targetEntity=HorseType::class, inversedBy="horseSchleiches")
     * @ORM\JoinColumn(nullable=false)
     * @var HorseType
     */
    private HorseType $type;

    /**
     * @ORM\ManyToOne(targetEntity=HorseCoat::class, inversedBy="horseSchleiches")
     * @ORM\JoinColumn(nullable=false)
     * @var HorseCoat
     */
    private HorseCoat $coat;

    /**
     * @ORM\ManyToOne(targetEntity=HorseSpecies::class, inversedBy="horseSchleiches")
     * @ORM\JoinColumn(nullable=false)
     * @var HorseSpecies
     */
    private HorseSpecies $species;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="horseSchleiches")
     * @ORM\JoinColumn(nullable=false)
     * @var User
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=ObjectFamily::class, inversedBy="horseSchleich")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="code", name="object_family_code")
     */
    private ?ObjectFamily $objectFamily = null;

    /**
     * @Vich\UploadableField(mapping="uploaded_images", fileNameProperty="imageName")
     * @Assert\File(maxSize="1024k")
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
     * @return HorseType
     */
    public function getType(): HorseType
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return HorseCoat
     */
    public function getCoat(): HorseCoat
    {
        return $this->coat;
    }

    /**
     * @param $coat
     * @return $this
     */
    public function setCoat($coat): self
    {
        $this->coat = $coat;

        return $this;
    }

    /**
     * @return HorseSpecies
     */
    public function getSpecies(): HorseSpecies
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
