<?php
declare(strict_types=1);


namespace App\DataTransformer;

use App\Dto\Audio2TextDto;
use App\Entity\Audio;
use App\Entity\AudioText;
use App\Entity\AudioTextDetail;
use App\Entity\AudioTextDetailBadWord;

class Audio2TextDtoToAudioTextDataTransformer
{
    public static function transform(Audio2TextDto $audio2TextDto, Audio $audio): AudioText
    {
        $audioText = new AudioText();
        $audioText->setBody($audio2TextDto->getText());
        $lengthFullText = mb_strlen($audio2TextDto->getText());

        $audioText->setTotalNumberOfCharacters($lengthFullText);

        $numberOfParts = count($audio2TextDto->getTextParts());


        for ($i = 0; $i < $numberOfParts; $i++) {
            $audioTextDetail = new AudioTextDetail();
            $audioTextDetail->setText($audio2TextDto->getTextParts()[$i]);
            $audioTextDetail->setStartAt(self::convertBitrateToMilliseconds($audio2TextDto->getTextPartCoords()[$i][0]));
            $audioTextDetail->setEndAt(self::convertBitrateToMilliseconds($audio2TextDto->getTextPartCoords()[$i][1]));
            $audioTextDetail->setNumberOfCharacters(mb_strlen($audio2TextDto->getTextParts()[$i]));

            $offsetFull = 0;
            foreach ($audioText->getDetails() as $detail) {
                $offsetFull = $offsetFull + $detail->getNumberOfCharacters();
            }

            $audioTextDetail->setOffsetAtFromStartText($offsetFull);

            foreach ($audio2TextDto->getBadWords() as $badWordArray) {
                foreach ($badWordArray as $badWord) {
                    $audioTextDetail->addBadWord(self::getAudioTextDetailBadWord($badWord));
                }
            }

            $audioTextDetails[] = $audioTextDetail;
            $audioText->addAudioTextDetail($audioTextDetail);
        }

        $audioText->setAudio($audio);

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

    private static function convertBitrateToMilliseconds(int $bitrate): int
    {
        return intdiv($bitrate, 16);
    }
}
