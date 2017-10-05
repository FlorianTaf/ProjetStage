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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadSubscriber implements EventSubscriber
{
    /**
     * @var UploadAnnotationReader
     */
    private $reader;

    public function __construct(UploadAnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'postLoad', //Pour pouvoir hydrater notre entité Personne avec le file
            'postUpdate'
        ];
    }

    public function prePersist(EventArgs $event)
    {
        $entity = $event->getEntity();
        //On récupère la propriété et en valeur l'annotation
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            //On va faire appel à la classe qui permet d'accéder aux getters et setters
            $accessor = PropertyAccess::createPropertyAccessor();
            $file = $accessor->getValue($entity, $property);
            if ($file instanceof UploadedFile) {
                $filename = $file->getClientOriginalName();
                //Le 1er argument est le chemin, le 2ème le nom qu'on lui donne (on lui donne ici le même nom que celui d'origine)
                $file->move($annotation->getPath(), $filename);
                //On met à jour notre filename (le champ de notre bdd lié au fichier (1er argument l'entité, 2ème le champ qu'on veut modifier, 3ème la valeur qu'on lui donne)
                $accessor->setValue($entity, $annotation->getFilename(), $filename);
            }
        }
    }

    public function postLoad(EventArgs $event)
    {
        $entity = $event->getEntity();
        //On récupère la propriété et en valeur l'annotation
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            //On va faire appel à la classe qui permet d'accéder aux getters et setters
            $accessor = PropertyAccess::createPropertyAccessor();
            //On récupère le nom du fichier
            $filename = $accessor->getValue($entity, $annotation->getFileName());
            if ($filename != null) {
                //On va générer le fichier avec le chemin correspondant
                $file = new File($annotation->getPath() . DIRECTORY_SEPARATOR . $filename); //On aurait pu utiliser le ' . "/" . ' à la place du directory separator
                //On hydrate notre objet avec le fichier
                $accessor->setValue($entity, $property, $file);
                dump($file);
            }
        }
    }

    public function postUpdate(EventArgs $event)
    {
        $entity = $event->getEntity();
        //On récupère la propriété et en valeur l'annotation
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            //On va faire appel à la classe qui permet d'accéder aux getters et setters
            $accessor = PropertyAccess::createPropertyAccessor();
            $file = $accessor->getValue($entity, $property);
            if ($file instanceof UploadedFile) {
                $filename = $file->getClientOriginalName();
                //Le 1er argument est le chemin, le 2ème le nom qu'on lui donne (on lui donne ici le même nom que celui d'origine)
                $file->move($annotation->getPath(), $filename);
                //On met à jour notre filename (le champ de notre bdd lié au fichier (1er argument l'entité, 2ème le champ qu'on veut modifier, 3ème la valeur qu'on lui donne)
                $accessor->setValue($entity, $annotation->getFilename(), $filename);
            }
        }
    }
}