<?php

namespace BastSys\CdnBundle\Entity;

use BastSys\CdnBundle\Service\IFileLinkService;
use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;

/**
 * Interface IFile
 * @package BastSys\CdnBundle\Entity
 * @author mirkl
 */
interface IFile extends IIdentifiableEntity
{
    /**
     * @return string
     */
    function getLink(): ?string;

    /**
     * @param IFileLinkService $fileLinkService
     */
    function setFileLinkService(IFileLinkService $fileLinkService): void;

    /**
     * @param string $name
     */
    function setName(string $name): void;

    /**
     * @return string|null
     */
    function getName(): ?string;

    /**
     * @param string|null $mimeType
     */
    function setMimeType(string $mimeType = null): void;

    /**
     * @return string
     */
    function getMimeType(): ?string;

    /**
     * @param int $size
     */
    function setSize(int $size): void;

    /**
     * @return int
     */
    function getSize(): int;
}
