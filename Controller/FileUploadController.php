<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Controller;

use BastSys\CdnBundle\Event\FileUploadedEvent;
use BastSys\CdnBundle\Security\FileUploadVoter;
use BastSys\CdnBundle\Service\IFileService;
use BastSys\CdnBundle\Structure\FileContainer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileUploadController
 * @package BastSys\CdnBundle\Controller
 * @author  mirkl
 */
class FileUploadController extends AbstractController
{
    const HEADER_FILE_NAME = 'X-File-Name';

    /** @var EntityManagerInterface */
    private $em;
    /**
     * @var IFileService
     */
    private $fileService;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * FileUploadController constructor.
     *
     * @param EntityManagerInterface $em
     * @param IFileService $fileService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, IFileService $fileService, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->fileService = $fileService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/file-upload", methods={"POST", "OPTIONS"})
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function handle(Request $request)
    {
        if ($request->getMethod() === Request::METHOD_OPTIONS) {
            return new Response(null, 200, [
                'Access-Control-Allow-Headers' => self::HEADER_FILE_NAME
            ]);
        }

        // process file data
        $fileName = urldecode(
            $request->headers->get(self::HEADER_FILE_NAME, '')
        );
        $fileName = $fileName ? $fileName : null; // '' > null
        $contentType = $request->headers->get('Content-Type');
        $fileContent = $request->getContent();

        $fileContainer = new FileContainer(
            $fileName,
            $contentType,
            $fileContent
        );

        // vote for upload
        $this->denyAccessUnlessGranted(FileUploadVoter::ATTRIBUTE, $fileContainer);

        // create local file
        $file = $this->fileService->createFile($fileContainer);

        // dispatch uploaded event
        $event = new FileUploadedEvent($file);
        try {
            $this->eventDispatcher->dispatch($event);
        } catch(\Exception $ex) {
            // delete file on exception
            $this->fileService->deleteFile($file);
            throw $ex;
        }

        // flush database if needed
        if($this->em->isOpen()) {
            $this->em->flush();
        }

        return new JsonResponse([
            'id' => $file->getId(),
            'name' => $file->getName(),
            'mimeType' => $file->getMimeType(),
            'link' => $file->getLink(),
            'size' => $file->getSize(),
        ], 201);
    }
}
