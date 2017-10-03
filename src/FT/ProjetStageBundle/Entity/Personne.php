<?php

namespace FT\ProjetStageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FT\UploadBundle\Annotation\Uploadable;
use FT\UploadBundle\Annotation\UploadableField;


/**
 * Personne
 *
 * @ORM\Table(name="personne")
 * @ORM\Entity(repositoryClass="FT\ProjetStageBundle\Repository\PersonneRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="typePersonne", type="string")
 * @ORM\DiscriminatorMap({"personne" = "Personne", "etudiant" = "Etudiant", "formateur" = "Formateur", "admin" = "Admin"})
 * @ORM\HasLifecycleCallbacks()
 * @Uploadable()
 */
abstract class Personne implements AdvancedUserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=45)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=45)
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=45)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20)
     */
    protected $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, unique=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="FT\ProjetStageBundle\Entity\Role", inversedBy="personnes")
     */
    protected $role;

    /**
     * @var datetime
     *
     * @ORM\Column(name="dateUpdate", type="datetime", nullable=true)
     */
    protected $dateUpdate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="dateInscription", type="datetime", nullable=true)
     */
    protected $dateInscription;

    /**
     * @ORM\OneToMany(targetEntity="FT\ProjetStageBundle\Entity\Message", mappedBy="sender")
     */
    protected $messages;

    /**
     * @var string
     * @Assert\File(
     *     maxSize = "700k",
     *     mimeTypes = {"application/png", "application/jpg", "application/jpeg"},
     *     mimeTypesMessage = "Veuillez sélectionner une image (png, jpg ou jpeg)"
     * )
     * @ORM\Column(name="filename", type="string", length=255)
     */
    protected $filename;

    /**
     * @UploadableField(filename="filename", path="uploads/images")
     */
    protected $file;


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
     * @return Personne
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Personne
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Personne
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Personne
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Personne
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set role
     *
     * @param \FT\ProjetStageBundle\Entity\Role $role
     *
     * @return Personne
     */
    public function setRole(\FT\ProjetStageBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }


    /**
     * Get role
     *
     * @return \FT\ProjetStageBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getRoles()
    {
        return array($this->role->getName());
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return true;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Personne
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
 * @ORM\PreUpdate
 */
    public function updateDate()
    {
        $this->setDateUpdate(new \DateTime());
    }

    /**
     * @ORM\PrePersist
     */
    public function updateDateInscription()
    {
        $this->setDateInscription(new \DateTime());
    }

    /**
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     *
     * @return Personne
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add message
     *
     * @param \FT\ProjetStageBundle\Entity\Message $message
     *
     * @return Personne
     */
    public function addMessage(\FT\ProjetStageBundle\Entity\Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \FT\ProjetStageBundle\Entity\Message $message
     */
    public function removeMessage(\FT\ProjetStageBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Personne
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file|null
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
}
