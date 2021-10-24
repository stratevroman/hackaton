<?php
declare(strict_types=1);


namespace App\Service;

use App\DataTransformer\Audio2TextDtoToAudioTextDataTransformer;
use App\Dto\Audio2TextDto;
use App\Entity\Audio;
use App\Entity\AudioText;
use App\Helper\AddingItemToAudioTextHelper;
use App\Helper\CompareAudioTextHelper;
use App\Repository\AudioRepository;

class AudioService
{
    private AudioRepository $audioRepository;

    public function __construct(AudioRepository $audioRepository)
    {
        $this->audioRepository = $audioRepository;
    }

    public function saveAudioText(Audio $audio, Audio2TextDto $audio2TextDto): Audio
    {
        $newAudioText = Audio2TextDtoToAudioTextDataTransformer::transform($audio2TextDto);

        if ($audio->getAudioText() === null) {
            $audio->setAudioText($newAudioText);
        } else {
            if (!CompareAudioTextHelper::isSame($audio->getAudioText(), $newAudioText)) {
                $audio = AddingItemToAudioTextHelper::addingItem($audio->getAudioText(), $newAudioText);
            }
        }

        return $this->audioRepository->save($audio);
    }
}
