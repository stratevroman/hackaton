<?php

namespace App\Controller;

use App\Dto\AudioRequestDto;
use App\Dto\AudioResponseDto;
use App\Dto\AudioDocumentResponseDto;
use App\Entity\Audio;
use App\Entity\AudioText;
use App\Repository\AudioRepository;
use App\Service\AudioService;
use App\Service\DocumentGeneratorService;
use App\Service\ProcessCoreService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use App\Dto\Audio2TextDto;

class AudioController extends AbstractController
{
    private DocumentGeneratorService $documentGenerator;
    private AudioRepository $audioRepository;
    private ProcessCoreService $processCoreService;
    private AudioService $audioService;

    public function __construct(
        DocumentGeneratorService $documentGenerator,
        AudioRepository $audioRepository,
        ProcessCoreService $processCoreService,
        AudioService $audioService
    ) {
        $this->documentGenerator = $documentGenerator;
        $this->audioRepository = $audioRepository;
        $this->processCoreService = $processCoreService;
        $this->audioService = $audioService;
    }

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
    public function saveAudio(Request $request): AudioResponseDto
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

        $audio = (new Audio())
            ->setName($newFileName)
            ->setUrl($path);

        $audio = $this->audioRepository->save($audio);

        $audioRequestDto = new AudioRequestDto($path, $newFileName, $language, $isLast, $audio->getId());

        $response = $this->processCoreService->getTextByAudioAsync($audioRequestDto);

        if ($response->getError() !== null) {
            throw new \Exception('Ошибка запроса. Detail: '.$response->getError());
        }

        return new AudioResponseDto($audio->getId());
    }

    /**
     * @Route("/audio/{id}", name="get audio information by audio id", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Информация по аудио",
     *     @Model(type=Audio::class)
     * )
     */
    public function getAudio(int $id): Audio
    {
        $audio = $this->audioRepository->find($id);

        if ($audio === null) {
            throw new NotFoundHttpException('Аудио не найдено');
        }

        return $audio;
    }

    /**
     * @Route("/audio/", name="get all audios information", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Информация по всем аудио",
     *     @Model(type=Audio::class)
     *     )
     */
    public function getAudios(): array
    {
        return $this->audioRepository->findAll();
    }

    /**
     * @Route("/audio/{id}/text", name="get text by audio id", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Текст аудио",
     *     @Model(type=AudioText::class)
     * )
     */
    public function getTextByAudioId(int $id): AudioText
    {
        $audio = $this->audioRepository->find($id);

        if ($audio === null) {
            throw new NotFoundHttpException('Аудио не найдено');
        }

        $textAudio = $audio->getAudioText();

        if ($textAudio === null) {
            throw new NotFoundHttpException('Текст аудио не найдено');
        }

        return $textAudio;
    }

    /**
     * @Route("/audio/{id}/text", name="post text by audio id", methods={"POST"})
     * @OA\RequestBody(
     *     description="Parameters",
     *     required=true,
     *     @Model(type=Audio2TextDto::class)
     *
     * )
     * @OA\Response(
     *     response="200",
     *     description="Текст аудио",
     *     @Model(type=AudioText::class)
     * )
     */
    public function postAudioText(int $id, Audio2TextDto $audio2TextDto): AudioText
    {
        $audio = $this->audioRepository->find($id);

        if ($audio === null) {
            throw new NotFoundHttpException('Аудио не найдено');
        }

        return $this->audioService->saveAudioText($audio, $audio2TextDto)->getAudioText();
    }

    /**
     * @Route("/audio/{id}/document", name="get document by audio id", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Информация о документе аудио",
     *     @Model(type=AudioDocumentResponseDto::class)
     * )
     * @throws \Exception
     */
    public function getDocumentByAudioId(int $id): AudioDocumentResponseDto
    {
        $audio = $this->audioRepository->find($id);

        if ($audio === null) {
            throw new \Exception('Аудио не найдено');
        }

        $audioText = $audio->getAudioText();

        if ($audioText === null) {
            throw new \Exception('Текст Аудио не найден');
        }

        $documentUrl = $this->documentGenerator->generateDocumentByAudioText($audioText);

        return new AudioDocumentResponseDto($documentUrl);
    }
}
