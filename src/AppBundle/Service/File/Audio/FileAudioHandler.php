<?php
namespace AppBundle\Service\File\Audio;

use AppBundle\Entity\Song;
use AppBundle\Service\File\General\FileHandler;

/**
 * This class is dependant on Song Entity
 */
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
    public function newAudioFile(Song $song)
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

    protected function removeAudioFile(Song $song)
    {
        $audioFilePath = $this->audioDirectory . Song::AUDIOFILEPATH . $song->getAudioFile();

        $this->removeFile($audioFilePath);
    }

    protected function removeCoverFile(Song $song)
    {
        $audioFilePath = $this->audioDirectory . Song::COVERFILEPATH . $song->getCover();

        $this->removeFile($audioFilePath);
    }

    public function removeSongEntityRelatedFiles(Song $song)
    {
        $this->removeAudioFile($song);

        if ($song->getCover()) {
            $this->removeCoverFile($song);
        }
    }
}