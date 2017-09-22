<?php

namespace FT\ProjetStageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etudiant
 *
 * @ORM\Table(name="etudiant")
 * @ORM\Entity(repositoryClass="FT\ProjetStageBundle\Repository\EtudiantRepository")
 */
class Etudiant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="FT\ProjetStageBundle\Entity\Equipe", mappedBy="proprietaire")
     */
    private $equipes;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->equipes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add equipe
     *
     * @param \FT\ProjetStageBundle\Entity\Etudiant $equipe
     *
     * @return Etudiant
     */
    public function addEquipe(\FT\ProjetStageBundle\Entity\Etudiant $equipe)
    {
        $this->equipes[] = $equipe;

        return $this;
    }

    /**
     * Remove equipe
     *
     * @param \FT\ProjetStageBundle\Entity\Etudiant $equipe
     */
    public function removeEquipe(\FT\ProjetStageBundle\Entity\Etudiant $equipe)
    {
        $this->equipes->removeElement($equipe);
    }

    /**
     * Get equipes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipes()
    {
        return $this->equipes;
    }
}
