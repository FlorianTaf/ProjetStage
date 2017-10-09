<?php

namespace FT\ProjetStageBundle\Repository;

/**
 * EquipeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EquipeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getEquipesParticipantes($idProjet)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.projets', 'p')
            ->addSelect('p')
            ->where('p.id = :idProjet')->setParameter('idProjet', $idProjet)
            ->getQuery()->getResult();
    }

    public function getLastThree()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.dateCreation', 'DESC')
            ->setMaxResults(3)
            ->getQuery()->getResult();
    }
}
