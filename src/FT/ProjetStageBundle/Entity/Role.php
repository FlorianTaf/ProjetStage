<?php

namespace FT\ProjetStageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="FT\ProjetStageBundle\Repository\RoleRepository")
 */
class Role
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
     * @ORM\Column(name="name", type="string", length=30, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="FT\ProjetStageBundle\Entity\Personne", mappedBy="role", cascade={"persist"})
     */
    private $personnes;

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
     * Set name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personnes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add personne
     *
     * @param \FT\ProjetStageBundle\Entity\Personne $personne
     *
     * @return Role
     */
    public function addPersonne(\FT\ProjetStageBundle\Entity\Personne $personne)
    {
        $this->personnes[] = $personne;

        return $this;
    }

    /**
     * Remove personne
     *
     * @param \FT\ProjetStageBundle\Entity\Personne $personne
     */
    public function removePersonne(\FT\ProjetStageBundle\Entity\Personne $personne)
    {
        $this->personnes->removeElement($personne);
        $personne->setRole($this);
    }

    /**
     * Get personnes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonnes()
    {
        return $this->personnes;
    }
}
