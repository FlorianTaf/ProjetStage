<?php

namespace FT\ProjetStageBundle\Repository;

/**
 * PersonneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonneRepository extends \Doctrine\ORM\EntityRepository
{
    public function loadEtudiants()
    {
        $qb = $this->createQueryBuilder('p');
        return $qb
            ->where($qb->expr()->isNotNull('p.etudiant'))
            ->getQuery()
            ->getResult();
    }
}
