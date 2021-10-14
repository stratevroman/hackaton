<?php

namespace App\Controller;

use App\Dto\AudioRequestDto;
use App\Dto\AudioResponseDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class AudioController extends AbstractController
{
    public const AUDIO_2_TEXT_API = 'http://sel3-common-ml-1.skyeng.link:5000/audio2text';

    /**
     * @Route("/audio", name="audio", methods={"POST"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Возвращает текст",
     *     @Model(type=AudioResponseDto::class)
     * )
     *
     *
     * @OA\RequestBody(
     *     description="Parameters",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *             required={"audio", "isRussianLanguage", "isLast"},
     *             @OA\Property(
     *                      property="audio",
     *                      description="аудиофайл в формате wav",
     *                      type="file",
     *              ),
     *              @OA\Property(
     *                      property="isRussianLanguage",
     *                      description="русский язык, если false - то английский",
     *                      type="boolean",
     *              ),
     *              @OA\Property(
     *                      property="isLast",
     *                      description="true - если последний кусок аудио",
     *                      type="boolean",
     *              )
     *          )
     *     ),
     *
     * )
     */
    public function index(Request $request): AudioResponseDto
    {
        $language = $request->get('isRussianLanguage') === 'true';

        $isLast = $request->get('isLast') === 'true';

        /** @var UploadedFile $audioFile */
        $audioFile = $request->files->get('audio');
        if ($audioFile === null) {
            throw new BadRequestHttpException();
        }

        $newFileName = 'audio1.wav';
        $audioFile->move($this->getParameter('audio_directory'), $newFileName);
        $path = $this->getParameter('audio_directory').'/'.$newFileName;

        $audioRequestDto = new AudioRequestDto($path, $newFileName, $language, $isLast);

        $response = $this->sendAudioToProcessingService($audioRequestDto);

        return new AudioResponseDto($response['Text']);
    }

    private function sendAudioToProcessingService(AudioRequestDto $audioData): array
    {
        $client = new Client();

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
            $response = $client->request('POST', self::AUDIO_2_TEXT_API, [
                'multipart' => $multipartParams,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['error' => 'Упс, произошла ошибка', 'detail' => $e];
        }
    }
}
