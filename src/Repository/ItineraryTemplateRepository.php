<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ItineraryTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItineraryTemplate>
 */
class ItineraryTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItineraryTemplate::class);
    }
}
