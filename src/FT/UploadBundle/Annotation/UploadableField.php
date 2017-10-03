<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 03/10/2017
 * Time: 10:35
 */

namespace FT\UploadBundle\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class UploadableField
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $path;


    public function __construct(array $options)
    {
        if (empty($options['filename'])) {
            throw new \InvalidArgumentException("L'annotation 'UploadableField' doit avoir un attribut 'filename'");
        }
        if (empty($options['path'])) {
            throw new \InvalidArgumentException("L'annotation 'UploadableField' doit avoir un attribut 'path'");
        }

        $this->filename = $options['filename'];
        $this->path = $options['path'];
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}