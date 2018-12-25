<?php
namespace AppBundle\Service\File\General;

use AppBundle\Entity\Song;
use Symfony\Component\HttpFoundation\File\File;

interface FileHandlerInterface
{
    /**
     * Store new File $file at given $path
     *
     * @param File $file
     * @param string $path
     * @return string
     */
    public function newFile(File $file, string $path);

    /**
     * Remove file at given $path
     * 
     * @param string $path
     */
    public function removeFile($path);

}