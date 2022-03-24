<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait SluggableTrait
{
    /**
     * @Gedmo\Slug(fields={"createdAt", "name"}, style="default", separator="-", updatable=true, unique=true, dateFormat="d/m/Y H-i-s")
     * @ORM\Column(length=128, unique=true)
     */
    private ?string $slug = null;

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        dd($this->slug);
        return $this->slug;
    }

    /**
     * @param string|null $slug
     * @return $this
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
