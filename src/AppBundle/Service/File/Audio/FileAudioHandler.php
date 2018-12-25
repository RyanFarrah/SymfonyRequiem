<?php
namespace AppBundle\Service\File\Audio;

use AppBundle\Entity\Song;
use AppBundle\Service\File\General\FileHandler;

class FileAudioHandler extends FileHandler
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

        $audioPath = $this->audioDirectory . $song::AUDIOFILEPATH;

        return $this->newFile($audioFile, $audioPath);
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

    public function removeAudioFile(Song $song)
    {
        $song->getAudioFile();

        if (!unlink($this->audioDirectory . Song::AUDIOFILEPATH . $song->getAudioFile)) {
            throw new \Exception('Un fichier n\'a pas pu être supprimé');
        }
    }
}