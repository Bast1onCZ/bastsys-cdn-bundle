<?php

namespace BastSys\CdnBundle\Controller;

use BastSys\CdnBundle\Service\IFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FileUploadController
 * @package BastSys\CdnBundle\Controller
 * @author  mirkl
 */
class FileUploadController
{
    const HEADER_FILE_NAME = 'X-File-Name';

    /** @var EntityManagerInterface */
    private $em;
    /**
     * @var IFileService
     */
    private $fileService;

    /**
     * FileUploadController constructor.
     *
     * @param EntityManagerInterface $em
     * @param IFileService $fileService
     */
    public function __construct(EntityManagerInterface $em, IFileService $fileService)
    {
        $this->em = $em;
        $this->fileService = $fileService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        if ($request->getMethod() === Request::METHOD_OPTIONS) {
            return new Response(null, 200, [
                'Access-Control-Allow-Headers' => self::HEADER_FILE_NAME
            ]);
        }

        $headers = $request->headers;
        $fileName = $headers->get(self::HEADER_FILE_NAME);
        $contentType = $headers->get('Content-Type');
        $fileContent = $request->getContent();

        $file = $this->fileService->createFile($fileContent);
        if ($fileName) {
            $file->setName(
                urldecode($fileName) // decoding file name in case it contains percent-encoded characters
            );
        }
        $file->setMimeType($contentType);

        return new JsonResponse([
            'id' => $file->getId(),
            'name' => $file->getName(),
            'mimeType' => $file->getMimeType(),
            'link' => $file->getLink(),
            'size' => $file->getSize(),
        ], 201);
    }
}
