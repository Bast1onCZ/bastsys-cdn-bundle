<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Service;

use BastSys\CdnBundle\Entity\IFile;

/**
 * Interface IFileLinkService
 * @package BastSys\CdnBundle\Service
 * @author mirkl
 */
interface IFileLinkService
{
    /**
     * @param IFile $file
     * @return string|null
     */
    function getFileLink(IFile $file): ?string;
}
