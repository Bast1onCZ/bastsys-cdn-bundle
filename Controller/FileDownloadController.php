<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Controller;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Service\IFileService;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class FileDownloadController
 * @package BastSys\CdnBundle\Controller
 * @author mirkl
 */
class FileDownloadController extends AbstractController
{
    const FILE_DOWNLOAD_PERMISSION = 'bastsys-cdn-file-download-permission';

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
     * @Route("/file-download/{id}", methods={"GET"})
     *
     * @param string $id
     * @return Response
     * @throws \BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException
     */
    public function handle(string $id)
    {
        $this->denyAccessUnlessGranted(self::FILE_DOWNLOAD_PERMISSION, $id);

        $this->stopwatch->start(self::LAP_FILE_DEFINITION_LOAD);
        /** @var IFile $file */
        $file = $this->fileRepository->findById($id, true);
        $this->stopwatch->stop(self::LAP_FILE_DEFINITION_LOAD);

        $this->stopwatch->start(self::LAP_FILE_CONTENT_LOAD);
        $fileContents = $this->fileService->getContents($file);
        $this->stopwatch->stop(self::LAP_FILE_CONTENT_LOAD);

        if ($fileContents === null) {
            throw new NotFoundHttpException("File '$id' was not found");
        }

        return new Response(
            $fileContents,
            200,
            [
                'Content-Type' => $file->getMimeType()
            ]
        );
    }
}
