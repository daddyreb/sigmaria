<?php

namespace App\Entity\Core;

use App\Entity\Core\Traits\BaseFieldsTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Core\ActionsRepository")
 * @ORM\Table(name="core_actions")
 */
class Actions
{
    use BaseFieldsTrait;

    public const ACTIONS_ONE = 'ACTIONS_ONE';
    public const ACTIONS_TWO = 'ACTIONS_TWO';
    public const ACTIONS_THREE = 'ACTIONS_THREE';
    public const ACTIONS_REACTION = 'ACTIONS_REACTION';
    public const ACTIONS_FREE = 'ACTIONS_FREE';

    public function __construct()
    {
        $this->isActive = true;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
}