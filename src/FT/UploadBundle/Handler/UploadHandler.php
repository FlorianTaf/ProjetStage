<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 05/10/2017
 * Time: 13:20
 */

namespace FT\UploadBundle\Handler;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadHandler
{
    private $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function uploadFile($entity, $property, $annotation)
    {
        //On va faire appel à la classe qui permet d'accéder aux getters et setters
        $file = $this->accessor->getValue($entity, $property);
        if ($file instanceof UploadedFile) {
            $this->handler->removeOldFile($entity, $annotation);
            $filename = $file->getClientOriginalName();
            //Le 1er argument est le chemin, le 2ème le nom qu'on lui donne (on lui donne ici le même nom que celui d'origine)
            $file->move($annotation->getPath(), $filename);
            //On met à jour notre filename (le champ de notre bdd lié au fichier (1er argument l'entité, 2ème le champ qu'on veut modifier, 3ème la valeur qu'on lui donne)
            $this->accessor->setValue($entity, $annotation->getFilename(), $filename);
        }
    }

    public function setFileFromFilename($entity, $property, $annotation)
    {
        $file = $this->getFileFromFileName($entity, $annotation);
        $this->accessor->setValue($entity, $property, $file);
    }

    public function removeOldFile($entity, $annotation)
    {
        $file = $this->getFileFromFileName($entity, $annotation);
        if ($file !== null) {
            @unlink($file->getRealPath());
        }
    }

    public function removeFile($entity, $property)
    {
        $file = $this->accessor->getValue($entity, $property);
        if ($file instanceof File) {
            @unlink($file->getRealPath());
        }
    }

    //Fonction pour récupérer le fichier en fonction du nom de fichier
    private function getFileFromFileName($entity, $annotation)
    {
        $filename = $this->accessor->getValue($entity, $annotation->getFilename());
        if (empty($filename)) {
            return null;
        } else {
            return new File($annotation->getPath() . DIRECTORY_SEPARATOR . $filename, false); //On meet le check path à false pour éviter qu'il vérifie si le fichier existe
        }
    }
}