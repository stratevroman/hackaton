<?php
declare(strict_types=1);


namespace App\DataTransformer;

use App\Dto\Audio2TextDto;
use App\Entity\AudioText;
use App\Entity\AudioTextDetail;
use App\Entity\AudioTextDetailBadWord;

class Audio2TextDtoToAudioTextDataTransformer
{
    public static function transform(Audio2TextDto $audio2TextDto): AudioText
    {
        $audioText = new AudioText();
        $audioText->setBody($audio2TextDto->getText());

        $numberOfParts = count($audio2TextDto->getTextParts());

        for ($i = 0; $i < $numberOfParts; $i++) {
            $audioTextDetail = new AudioTextDetail();
            $audioTextDetail->setText($audio2TextDto->getTextParts()[$i]);
            $audioTextDetail->setEndAt($audio2TextDto->getTextPartCoords()[$i][1]);
            $audioTextDetail->setStartAt($audio2TextDto->getTextPartCoords()[$i][0]);
            $audioTextDetail->setNumberOfCharacters(strlen($audio2TextDto->getTextParts()[$i]));
            $audioTextDetail->setOffsetAtFromStartText(0);

            foreach ($audio2TextDto->getBadWords() as $badWordArray) {
                foreach ($badWordArray as $badWord) {
                    $audioTextDetail->addBadWord(self::getAudioTextDetailBadWord($badWord));
                }
            }

            $audioTextDetails[] = $audioTextDetail;
            $audioText->addAudioTextDetail($audioTextDetail);
        }

        return $audioText;
    }

    private static function getAudioTextDetailBadWord(string $badWord): AudioTextDetailBadWord
    {
        $audioTextDetailBadWord = new AudioTextDetailBadWord();
        $matches = explode(' - ', $badWord);
        $audioTextDetailBadWord->setIndex((int)$matches[0]);
        $audioTextDetailBadWord->setWord($matches[1]);

        return $audioTextDetailBadWord;
    }
}
