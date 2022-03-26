<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\SluggableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use function unserialize;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"name"}, message="Ce pseudo est déjà utilisé par un autre utilisateur !")
 * @Vich\Uploadable
 */
class User extends AbstractImageFile implements UserInterface, PasswordAuthenticatedUserInterface, Serializable
{
    use SluggableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(type="json")
     * @var ArrayCollection
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @var string
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private string $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private ?string $about;

    /**
     * @ORM\OneToMany(targetEntity=Petshop::class, mappedBy="user")
     */
    private Collection $petshops;

    /**
     * @ORM\OneToMany(targetEntity=HorseSchleich::class, mappedBy="user")
     */
    private Collection $horseSchleiches;


    /**
     * @Vich\UploadableField (mapping="uploaded_images", fileNameProperty="imageName")
     * @Assert\File(maxSize="1024k")
     * @var File|null
     */
    protected ?File $file = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private ?string $resetToken;

    public function __construct()
    {
        $this->petshops = new ArrayCollection();
        $this->horseSchleiches = new ArrayCollection();
    }

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
    public function setName($name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->name;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->name;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param $roles
     * @return $this
     */
    public function setRoles($roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     *
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @param $about|null
     * @return $this
     */
    public function setAbout(?string $about): self
    {
        $this->about = $about;

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
     * @param Petshop $petshop
     * @return $this
     */
    public function addPetshop(Petshop $petshop): User
    {
        if (!$this->petshops->contains($petshop)) {
            $this->petshops[] = $petshop;
            $petshop->setUser($this);
        }
        return $this;
    }

    /**
     * @param Petshop $petshop
     * @return $this
     */
    public function removePetshop(Petshop $petshop): User
    {
        if ($this->petshops->removeElement($petshop)) {
            // set the owning side to null (unless already changed)
            if ($petshop->getUser() === $this) {
                $petshop->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getHorseSchleiches(): Collection
    {
        return $this->horseSchleiches;
    }

    /**
     * @param HorseSchleich $horseSchleich
     * @return $this
     */
    public function addHorseSchleich(HorseSchleich $horseSchleich): User
    {
        if (!$this->horseSchleiches->contains($horseSchleich)) {
            $this->horseSchleiches[] = $horseSchleich;
            $horseSchleich->setUser($this);
        }
        return $this;
    }

    /**
     * @param HorseSchleich $horseSchleich
     * @return $this
     */
    public function removeHorseSchleich(HorseSchleich $horseSchleich): User
    {
        if ($this->horseSchleiches->removeElement($horseSchleich)) {
            // set the owning side to null (unless already changed)
            if ($horseSchleich->getUser() === $this) {
                $horseSchleich->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string|null
     */
    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->name,
            $this->email,
            $this->password,
        ]);
    }

    /**
     * @param $data
     * @return void
     */
    public function unserialize($data)
    {
        [
            $this->id,
            $this->name,
            $this->email,
            $this->password,
        ] = unserialize($data, [self::class]);
    }

    public function setResetToken(?string $token): self
    {
        $this->resetToken = $token;

        return $this;
    }



}
