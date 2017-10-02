<?php

namespace FT\ProjetStageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="FT\ProjetStageBundle\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePosted", type="datetime")
     */
    private $datePosted;

    /**
     * @ORM\ManyToOne(targetEntity="FT\ProjetStageBundle\Entity\Personne", inversedBy="messages")
     */
    private $sender;


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
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set datePosted
     *
     * @param \DateTime $datePosted
     *
     * @return Message
     */
    public function setDatePosted($datePosted)
    {
        $this->datePosted = $datePosted;

        return $this;
    }

    /**
     * Get datePosted
     *
     * @return \DateTime
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * Set sender
     *
     * @param \FT\ProjetStageBundle\Entity\Personne $sender
     *
     * @return Message
     */
    public function setSender(\FT\ProjetStageBundle\Entity\Personne $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \FT\ProjetStageBundle\Entity\Personne
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateDate()
    {
        $this->setDatePosted(new \DateTime());
    }
}
