<?php

namespace App\Repository\Ancestry;

use App\Entity\Ancestry\AncestralFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class AncestralFeatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AncestralFeature::class);
    }
}
