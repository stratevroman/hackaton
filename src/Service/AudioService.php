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
use App\Repository\AudioTextRepository;

class AudioService
{
    private AudioRepository $audioRepository;
    private AudioTextRepository $audioTextRepository;

    public function __construct(AudioRepository $audioRepository, AudioTextRepository $audioTextRepository)
    {
        $this->audioRepository = $audioRepository;
        $this->audioTextRepository = $audioTextRepository;
    }

    public function saveAudioText(Audio $audio, Audio2TextDto $audio2TextDto): Audio
    {
        $newAudioText = Audio2TextDtoToAudioTextDataTransformer::transform($audio2TextDto, $audio);

        if ($audio->getAudioText() === null) {
            $audio->setAudioText($newAudioText);
        } else {
            if (!CompareAudioTextHelper::isSame($audio->getAudioText(), $newAudioText)) {
                $newAudioText = AddingItemToAudioTextHelper::addingItem($audio->getAudioText(), $newAudioText);
                $audio->setAudioText($newAudioText);
            }
        }

        return $this->audioRepository->save($audio);
    }

    public function updateAudioText(Audio $audio, AudioText $audioText): AudioText
    {
        $currentAudioText = $audio->getAudioText();

    }

}
