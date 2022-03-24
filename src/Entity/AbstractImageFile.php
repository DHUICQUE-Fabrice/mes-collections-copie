<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;

abstract class AbstractImageFile
{

    use TimestampableEntity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    protected ?string $imageName = null;

    protected ?File $file;

    /**
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     * @return $this
     */
    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return $this
     */
    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }
}
