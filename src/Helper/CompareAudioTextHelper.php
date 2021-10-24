<?php
declare(strict_types=1);


namespace App\Helper;

use App\Entity\AudioText;

class CompareAudioTextHelper
{
    public static function isSame(AudioText $audioTextFirst, AudioText $audioTextSecond): bool
    {
        return $audioTextFirst === $audioTextSecond;
    }
}
