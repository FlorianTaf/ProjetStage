<?php

namespace FT\ProjetStageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SessionFormation
 *
 * @ORM\Table(name="session_formation")
 * @ORM\Entity(repositoryClass="FT\ProjetStageBundle\Repository\SessionFormationRepository")
 */
class SessionFormation
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=45)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="FT\ProjetStageBundle\Entity\SessionFormation", mappedBy="sessionFormation")
     */
    private $projets;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return SessionFormation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add projet
     *
     * @param \FT\ProjetStageBundle\Entity\SessionFormation $projet
     *
     * @return SessionFormation
     */
    public function addProjet(\FT\ProjetStageBundle\Entity\SessionFormation $projet)
    {
        $this->projets[] = $projet;

        return $this;
    }

    /**
     * Remove projet
     *
     * @param \FT\ProjetStageBundle\Entity\SessionFormation $projet
     */
    public function removeProjet(\FT\ProjetStageBundle\Entity\SessionFormation $projet)
    {
        $this->projets->removeElement($projet);
    }

    /**
     * Get projets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjets()
    {
        return $this->projets;
    }
}
