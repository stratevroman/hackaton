<?php
declare(strict_types=1);


namespace App\Controller;

use App\Service\DocumentGeneratorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class DocumentController
{
    private DocumentGeneratorService $documentGenerator;

    public function __construct(DocumentGeneratorService $documentGenerator)
    {
        $this->documentGenerator = $documentGenerator;
    }

    /**
     * @Route("/document/generate", name="get document from text", methods={"POST"})
     *
     * @OA\RequestBody(
     *     description="Parameters",
     *     required=true,
     *     @OA\Schema(
     *       type="object",
     *       required={"text"},
     *          @OA\Property(
     *              property="text",
     *              description="text",
     *          @OA\Schema(
     *                      type="string",
     *                      default="text"
     *                   )
     *     )
     * )
     * )
     */
    public function documentGenerateByText(Request $request): bool
    {
        $text = $request->get('text');

        return $this->documentGenerator->generateDocument($text);
    }
}
