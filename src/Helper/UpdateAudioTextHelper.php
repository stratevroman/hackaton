<?php
declare(strict_types=1);


namespace App\Helper;

use App\Entity\AudioText;

class UpdateAudioTextHelper
{
    public static function updateAudioText(AudioText $currentAudioText, AudioText $newAudioText): AudioText
    {
        $resultAudioText = clone $currentAudioText;
        $resultAudioText->setBody($newAudioText->getBody());
        $resultAudioText->setTotalNumberOfCharacters(mb_strlen($newAudioText->getBody()));
        foreach ($resultAudioText->getDetails() as $detail) {

        }

        return $resultAudioText;
    }
}
