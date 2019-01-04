<?php

namespace tests\functional\AppBundle\Entity;

use AppBundle\Entity\Song;
use Symfony\Component\HttpFoundation\File\File;
use PHPUnit\Framework\TestCase;

class SongTest extends TestCase
{
    public function testTransformAudioFileToModelDataWhenViewData()
    {
        $song = new Song;

        $pathAudioFile = getcwd() . '/app/shared/files/test/uploads/audio/cover/cover.jpg';
        $audioFile = new File($pathAudioFile);

        $song->setAudioFile($audioFile);
        $song->transformAudioFileToModelData();

        $this->assertEquals('cover.jpg', $song->getAudioFile());
    }

    public function testTransformAudioFileToModelDataWhenModelData()
    {
        $song = new Song;

        $nameAudioFile = 'cover.jpg';

        $song->setAudioFile($nameAudioFile);
        $song->transformAudioFileToModelData();

        $this->assertEquals('cover.jpg', $song->getAudioFile());
    }

    public function testTransformAudioFileToModelDataFails()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Les données dans la propriété audioFileName ne peuvent pas être traitées');

        $song = new Song;

        $wrongData = 5;

        $song->setAudioFile($wrongData);
        $song->transformAudioFileToModelData();

    }
}