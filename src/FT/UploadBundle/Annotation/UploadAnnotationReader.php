<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 03/10/2017
 * Time: 22:25
 */

namespace FT\UploadBundle\Annotation;


use Doctrine\Common\Annotations\AnnotationReader;

class UploadAnnotationReader
{
    private $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function isUploadable($entity)
    {
        $reflection = new \ReflectionClass(get_class($entity));
        //On va vérifier qu'on a bien l'annotation uploadable sur l'entité passé en argument
        return $this->reader->getClassAnnotation($reflection, Uploadable::class) !== null; //Si c'est null, ça renverra faux
    }

    public function getUploadableFields($entity)
    {
        $reflection = new \ReflectionClass(get_class($entity));
        //Si c'est vide, on renvoie un tableau vide
        if (!$this->isUploadable($entity)) {
            return [];
        }
        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, UploadableField::class);
            if ($annotation !== null) {
                $properties[$property->getName()] = $annotation;
            }
        }
        return $properties;
    }
}