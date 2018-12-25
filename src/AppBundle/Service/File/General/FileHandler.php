<?php
namespace AppBundle\Service\File\General;

use AppBundle\Entity\Song;
use Symfony\Component\HttpFoundation\File\File;

class FileHandler implements FileHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function newFile(File $file, string $path)
    {

        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

        $file->move(
            $path,
            $fileName
        );

        return $fileName;
    }

    /**
     * {@inheritDoc}
     */
    public function removeFile($path)
    {

    }

    /**
     * @return string
     */
    protected function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}