<?php

namespace BastSys\CdnBundle\Service;

use BastSys\CdnBundle\Entity\IFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class UploadedFileLinkService implements IFileLinkService
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFileLink(IFile $file): ?string
    {
        if ($file->getSize() < 0) {
            return null; // if size < 0 file is not uploaded
        }

        return $this->router->generate('cdn_bundle.route.file_download', [
            'id' => $file->getId()
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
