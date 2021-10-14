<?php

namespace App\Controller;

use App\Dto\AudioRequestDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AudioController extends AbstractController
{
    public const AUDIO_2_TEXT_API = 'http://sel3-common-ml-1.skyeng.link:5000/audio2text';

    /**
     * @Route("/audio", name="audio", methods={"POST"})
     */
    public function index(Request $request): Response
    {
        $language = $request->get('language');
        $isLast = $request->get('isLast') ?? false;
        /** @var UploadedFile $audioFile */
        $audioFile = $request->files->get('audio');
        $newFileName = 'audio1.wav';
        $audioFile->move($this->getParameter('audio_directory'), $newFileName);
        $path = $this->getParameter('audio_directory').'/'.$newFileName;

        $audioRequestDto = new AudioRequestDto($path, $newFileName, $language, $isLast);

        $response = $this->sendAudioToProcessingService($audioRequestDto);

        return $this->json([
            'text' => $response['Text'] ?? '',
        ]);
    }

    private function sendAudioToProcessingService(AudioRequestDto $audioData): array
    {
        $client = new Client();

        $multipartParams = [
            [
                'name' => 'audio_data',
                'filename' =>  $audioData->getAudioFileName(),
                'contents' => fopen($audioData->getAudioFilePath(), 'r'),
            ],
            [
                'name' => 'is_last',
                'contents' => $audioData->isLast() ? 'True' : 'False',
            ],
            [
                'name' => 'language',
                'contents' => $audioData->getLanguage(),
            ],
        ];

        try {
            $response = $client->request('POST', self::AUDIO_2_TEXT_API, [
                'multipart' => $multipartParams,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['error' => 'Упс, произошла ошибка', 'detail' => $e];
        }
    }
}
