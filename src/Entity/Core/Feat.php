<?php

namespace App\Entity\Core;

use App\Entity\Core\Traits\BaseFieldsTrait;
use App\Entity\Core\Traits\SimpleRarityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Core\FeatRepository")
 * @ORM\Table(name="core_feat")
 */
class Feat
{
    use BaseFieldsTrait, SimpleRarityTrait, TimestampableEntity;

    /**
     * @var Actions
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Core\Actions")
     *
     * @Assert\NotBlank
     */
    private $actions;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\LessThanOrEqual(value="20")
     */
    private $level = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $prerequisites;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $frequency;

    /**
     * @var string|null
     *
     * @ORM\Column(name="`trigger`", type="string", nullable=true)
     */
    private $trigger;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $requirements;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $specialRules;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Core\Attribute")
     * @Assert\Count(min="1")
     */
    private $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * @return null|Actions
     */
    public function getActions(): ?Actions
    {
        return $this->actions;
    }

    /**
     * @param Actions $actions
     */
    public function setActions(Actions $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return string|null
     */
    public function getPrerequisites(): ?string
    {
        return $this->prerequisites;
    }

    /**
     * @param string|null $prerequisites
     */
    public function setPrerequisites(?string $prerequisites): void
    {
        $this->prerequisites = $prerequisites;
    }

    /**
     * @return string|null
     */
    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    /**
     * @param string|null $frequency
     */
    public function setFrequency(?string $frequency): void
    {
        $this->frequency = $frequency;
    }

    /**
     * @return string|null
     */
    public function getTrigger(): ?string
    {
        return $this->trigger;
    }

    /**
     * @param string|null $trigger
     */
    public function setTrigger(?string $trigger): void
    {
        $this->trigger = $trigger;
    }

    /**
     * @return string|null
     */
    public function getRequirements(): ?string
    {
        return $this->requirements;
    }

    /**
     * @param string|null $requirements
     */
    public function setRequirements(?string $requirements): void
    {
        $this->requirements = $requirements;
    }

    /**
     * @return string|null
     */
    public function getSpecialRules(): ?string
    {
        return $this->specialRules;
    }

    /**
     * @param string|null $specialRules
     */
    public function setSpecialRules(?string $specialRules): void
    {
        $this->specialRules = $specialRules;
    }

    /**
     * @return Collection|Attribute[]
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * @param ArrayCollection $attributes
     */
    public function setAttributes(ArrayCollection $attributes): void
    {
        $this->attributes = $attributes;
    }
}