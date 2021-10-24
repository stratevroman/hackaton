<?php
declare(strict_types=1);


namespace App\Service;

use App\Entity\AudioText;
use PhpOffice\PhpWord\PhpWord;

class DocumentGeneratorService
{
    public function generateDocumentByAudioText(AudioText $audioText): string
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText($audioText->getBody());

        $fileName = 'text.docx';

        if (!$phpWord->save($fileName)) {
            throw new \Exception('Не удалось сгенерировать документ');
        }

        return $fileName;
    }
}
