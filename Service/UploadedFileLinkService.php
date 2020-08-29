<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Service;

use BastSys\CdnBundle\Entity\IFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UploadedFileLinkService
 * @package BastSys\CdnBundle\Service
 * @author mirkl
 */
class UploadedFileLinkService implements IFileLinkService
{
    /** @var RouterInterface */
    private $router;

    /**
     * UploadedFileLinkService constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param IFile $file
     * @return string|null
     */
    public function getFileLink(IFile $file): ?string
    {
        if ($file->getSize() < 0) {
            return null; // if size < 0 file is not uploaded
        }

        return $this->router->generate('bastsys.cdn_bundle.route.file_download', [
            'id' => $file->getId()
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
