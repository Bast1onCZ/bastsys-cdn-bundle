<?php

namespace BastSys\CdnBundle\Controller;

use BastSys\CdnBundle\Service\IFileService;
use BastSys\CdnBundle\Structure\VirtualFile;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class FileDownloadController
 * @package BastSys\CdnBundle\Controller
 * @author mirkl
 */
class FileDownloadController
{
    const LAP_FILE_DEFINITION_LOAD = 'file definition load';
    const LAP_FILE_CONTENT_LOAD = 'file content load';

    /**
     * @var IFileService
     */
    private $fileService;

    /**
     * @var AEntityRepository
     */
    private $fileRepository;

    private $stopwatch;

    /**
     * FileDownloadController constructor.
     * @param IFileService $fileService
     * @param AEntityRepository $fileRepository
     * @param Stopwatch $stopwatch
     */
    public function __construct(IFileService $fileService, AEntityRepository $fileRepository, Stopwatch $stopwatch)
    {
        $this->fileService = $fileService;
        $this->fileRepository = $fileRepository;
        $this->stopwatch = $stopwatch;
    }

    /**
     * @param string $id
     * @param Request $request
     *
     * @return Response
     */
    public function handle(string $id)
    {
        $this->stopwatch->start(self::LAP_FILE_DEFINITION_LOAD);
        $file = new VirtualFile($id);
        $this->stopwatch->stop(self::LAP_FILE_DEFINITION_LOAD);

        $this->stopwatch->start(self::LAP_FILE_CONTENT_LOAD);
        $fileContents = $this->fileService->getContents($file);
        $this->stopwatch->stop(self::LAP_FILE_CONTENT_LOAD);

        if ($fileContents === null) {
            throw new NotFoundHttpException("File '$id' was not found");
        }

        $this->fileService->feedVirtualFile($file);

        return new Response(
            $fileContents,
            200,
            [
                'Content-Type' => $file->getMimeType()
            ]
        );
    }
}
