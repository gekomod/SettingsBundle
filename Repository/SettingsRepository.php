<?php

namespace Gekomod\SettingsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Gekomod\SettingsBundle\Entity\Settings;

/**
 * @method Settings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Settings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Settings[]    findAll()
 * @method Settings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingsRepository extends EntityRepository
{
}
