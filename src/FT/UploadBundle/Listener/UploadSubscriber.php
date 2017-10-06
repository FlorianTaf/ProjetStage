<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 03/10/2017
 * Time: 22:52
 */

namespace FT\UploadBundle\Listener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use FT\UploadBundle\Annotation\UploadAnnotationReader;
use FT\UploadBundle\Handler\UploadHandler;

class UploadSubscriber implements EventSubscriber
{
    /**
     * @var UploadAnnotationReader
     */
    private $reader;

    /**
     * @var UploadHandler
     */
    private $handler;

    public function __construct(UploadAnnotationReader $reader, UploadHandler $handler)
    {
        $this->reader = $reader;
        $this->handler = $handler;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postLoad', //Pour pouvoir hydrater notre entité Personne avec le file
            'postRemove'
        ];
    }

    public function prePersist(EventArgs $event)
    {
        $this->preEvent($event);
    }

    public function preUpdate(EventArgs $event)
    {
        $this->preEvent($event);
    }

    public function postLoad(EventArgs $event)
    {
        $entity = $event->getEntity();
        //On récupère la propriété et en valeur l'annotation
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->setFileFromFilename($entity, $property, $annotation);
        }
    }

    public function postRemove(EventArgs $event)
    {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->removeFile($entity, $property);
        }
    }

    //Méthode qui va nous servir pour le prePersist et preUpdate
    private function preEvent(EventArgs $event)
    {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->uploadFile($entity, $property, $annotation);
        }
    }
}