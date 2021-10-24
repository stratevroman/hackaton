<?php
declare(strict_types=1);


namespace App\Service;

use App\Dto\Audio2TextAsyncResponseDto;
use App\Dto\Audio2TextDto;
use App\Dto\AudioRequestDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JMS\Serializer\Serializer;

class ProcessCoreService
{
    public const CALLBACK_ADDRESS = 'http://localhost/api/v1/audio/{id}/text';
    private Serializer $serializer;
    private Client $client;

    public function __construct(Serializer $serializer, Client $client)
    {
        $this->serializer = $serializer;
        $this->client = $client;
    }

    public function getTextByAudio(AudioRequestDto $audioData): Audio2TextDto
    {
        $multipartParams = [
            [
                'name' => 'audio_data',
                'filename' => $audioData->getAudioFileName(),
                'contents' => fopen($audioData->getAudioFilePath(), 'r'),
            ],
            [
                'name' => 'is_last',
                'contents' => $audioData->isLast() ? 'True' : 'False',
            ],
            [
                'name' => 'language',
                'contents' => $audioData->isRussianLanguage() ? 'Ru' : 'En',
            ],
        ];

        try {
            $response = $this->client->request('POST', '/audio2text', [
                'multipart' => $multipartParams,
            ]);

            return $this->serializer->deserialize(
                $response->getBody()->getContents(),
                Audio2TextDto::class,
                'json'
            );
        } catch (GuzzleException $e) {
            throw new \Exception('Проблема при запросе на процессное приложение');
        }
    }

    public function getTextByAudioAsync(AudioRequestDto $audioData): Audio2TextAsyncResponseDto
    {
        $multipartParams = [
            [
                'name' => 'audio_data',
                'filename' => $audioData->getAudioFileName(),
                'contents' => fopen($audioData->getAudioFilePath(), 'r'),
            ],
            [
                'name' => 'is_last',
                'contents' => $audioData->isLast() ? 'True' : 'False',
            ],
            [
                'name' => 'language',
                'contents' => $audioData->isRussianLanguage() ? 'Ru' : 'En',
            ],
            [
                'name' => 'response_addr',
                'contents' => ''.str_replace('{id}', (string)$audioData->getAudioId(), self::CALLBACK_ADDRESS).'',
            ],
        ];

        try {
            $response = $this->client->request('POST', '/audio2text_async', [
                'multipart' => $multipartParams,
            ]);

            return $this->serializer->deserialize(
                $response->getBody()->getContents(),
                Audio2TextAsyncResponseDto::class,
                'json'
            );
        } catch (GuzzleException $e) {
            throw new \Exception('Проблема при запросе на процессное приложение (асинхронный режим). Детали: /n'. $e->getMessage());
        }
    }
}
