<?php

namespace App\Entity;

use App\Repository\UserProfileImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable()
 * @ORM\Entity(repositoryClass=UserProfileImageRepository::class)
 */
class UserProfileImage extends AbstractImageFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Vich\UploadableField(mapping="uploaded_images", fileNameProperty="imageName")
     * @var File|null
     */
    protected ?File $file;

    public function getId(): ?int
    {
        return $this->id;
    }
}
