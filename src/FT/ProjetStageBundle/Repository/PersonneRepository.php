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
        return $this->createQueryBuilder('p')
            ->where('p.etudiant_id IS NOT NULL')
            ->getQuery()
            ->getResult();
    }
}
