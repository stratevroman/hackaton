<?php
declare(strict_types=1);


namespace App\Helper;

use App\Entity\AudioText;

class AddingItemToAudioTextHelper
{
    public static function addingItem(AudioText $currentAudioText, AudioText $addingAudioText): AudioText
    {
        $newAudioText = $currentAudioText;

        $newAudioText->setBody($currentAudioText->getBody() . $addingAudioText->getBody());
        foreach ($addingAudioText->getDetails() as $audioTextDetail) {
            $newAudioText->addAudioTextDetail($audioTextDetail);
        }

        return $currentAudioText;
    }
}
