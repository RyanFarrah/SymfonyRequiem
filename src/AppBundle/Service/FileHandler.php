<?php
namespace AppBundle\Service;

use AppBundle\Entity\Song;

class FileHandler
{

    private $audioDirectory;

    public function __construct($audioDirectory) {
        $this->audioDirectory = $audioDirectory;
    }

    /**
     * Get the uploaded file and move him in appropriate folder, return the generated name of the file
     *
     * @param Song $song
     * @return string
     */
    public function newAudioFile($song)
    {
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $audioFile */
        $audioFile = $song->getAudioFile();

        $audioFileName = $this->generateUniqueFileName().'.'.$audioFile->guessExtension();

        $audioFile->move(
            $this->audioDirectory . $song::AUDIOFILEPATH,
            $audioFileName
        );

        return $audioFileName;
    }

    /**
     * Get the cover id3 of the audio file
     *
     * @param Song $song
     * @param string $audioFileName
     * @return string
     */
    public function getCoverFile(Song $song, string $audioFileName) {

        $getID3 = new \getID3;

        $ThisFileInfo = $getID3->analyze($this->audioDirectory . $song::AUDIOFILEPATH . $audioFileName);

        if(isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {

            $coverFileName = $this->generateUniqueFileName().'.'.'.jpeg';

            $coverFile = $this->audioDirectory . $song::COVERFILEPATH . $coverFileName;

            file_put_contents($coverFile, $ThisFileInfo['id3v2']['APIC'][0]['data']);

            return $coverFileName;

        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}