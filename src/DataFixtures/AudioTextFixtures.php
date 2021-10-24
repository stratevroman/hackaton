<?php

namespace App\DataFixtures;

use App\Entity\Audio;
use App\Entity\AudioText;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AudioTextFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $audio = (new Audio())
            ->setName('audio.mp3')
            ->setUrl('/audio.mp3');

        $audioText = (new AudioText())
            ->setBody('text')
            ->setAudio($audio);

        $manager->persist($audioText);

        $manager->flush();
    }
}
