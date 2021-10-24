<?php

namespace App\DataFixtures;

use App\Entity\Audio;
use App\Entity\AudioText;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AudioFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $audio = (new Audio())
            ->setName('audio.mp3')
            ->setUrl('/audio.mp3');

        $manager->persist($audio);

        $manager->flush();
    }
}
