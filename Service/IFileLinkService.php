<?php

namespace BastSys\CdnBundle\Service;

use BastSys\CdnBundle\Entity\IFile;

interface IFileLinkService
{
    function getFileLink(IFile $file): ?string;
}
