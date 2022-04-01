<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ReferentialTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private ?string $label = null;

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }
}