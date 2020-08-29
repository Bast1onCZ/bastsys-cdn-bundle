<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Controller;

use BastSys\CdnBundle\Event\FileUploadEvent;
use BastSys\CdnBundle\Service\IFileService;
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

        $event = new FileUploadEvent($file);
        try {
            $this->eventDispatcher->dispatch($event, $event::NAME);
        } catch(\Exception $ex) {
            $this->fileService->deleteFile($file);
            throw $ex;
        }

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
