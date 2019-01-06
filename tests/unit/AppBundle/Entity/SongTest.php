<?php

namespace tests\functional\AppBundle\Entity;

use AppBundle\Entity\Song;
use Symfony\Component\HttpFoundation\File\File;
use PHPUnit\Framework\TestCase;
use AppBundle\Entity\User;

class SongTest extends TestCase
{
    public function testTransformAudioFileToModelDataWhenViewData()
    {
        $song = new Song;

        $pathAudioFile = getcwd() . '/app/shared/files/test/uploads/audio/file/about_a_girl.mp3';
        $audioFile = new File($pathAudioFile);

        $song->setAudioFile($audioFile);
        $song->transformAudioFileToModelData();

        $this->assertEquals('about_a_girl.mp3', $song->getAudioFile());
    }

    public function testTransformAudioFileToModelDataWhenModelData()
    {
        $song = new Song;

        $nameAudioFile = 'about_a_girl.mp3';

        $song->setAudioFile($nameAudioFile);
        $song->transformAudioFileToModelData();

        $this->assertEquals('about_a_girl.mp3', $song->getAudioFile());
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

    public function testNewSong()
    {
        $song = new Song();

        $user = $this->createMock(User::class);
        $now = new \DateTime();

        $song->setAudioFile('song.mp3');
        $song->setCover('cover.jpg');
        $song->setAudioName('song');
        $song->setCreatedAt($now);
        $song->setUpdatedAt($now);
        $song->setUser($user);

        $this->assertEquals('song.mp3', $song->getAudioFile());
        $this->assertEquals('cover.jpg', $song->getCover());
        $this->assertEquals('song', $song->getAudioName());
        $this->assertEquals($now, $song->getCreatedAt());
        $this->assertEquals($now, $song->getUpdatedAt());
        $this->assertEquals($user, $song->getUser());
    }
}