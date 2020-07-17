<?php

namespace BastSys\CdnBundle\Structure;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Service\IFileLinkService;
use BastSys\UtilsBundle\Exception\NotImplementedException;

/**
 * Class VirtualFile
 * Contains information that can be determined from file saved at a disc.
 * More precise information should be stored in database (in case that the file really exists).
 * Identify VirtualFile with an ID and use IFileService::feedVirtualFile to load details.
 *
 * @package BastSys\CdnBundle\Entity
 * @author mirkl
 */
class VirtualFile implements IFile
{
    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $mimeType;

    /**
     * VirtualFile constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     * @throws NotImplementedException
     */
    function getLink(): ?string
    {
        throw new NotImplementedException();
    }

    /**
     * @param IFileLinkService $fileLinkService
     * @throws NotImplementedException
     */
    function setFileLinkService(IFileLinkService $fileLinkService): void
    {
        throw new NotImplementedException();
    }

    /**
     * @param string $name
     * @throws NotImplementedException
     */
    function setName(string $name): void
    {
        throw new NotImplementedException();
    }

    /**
     * @return string|null
     * @throws NotImplementedException
     */
    function getName(): ?string
    {
        throw new NotImplementedException();
    }

    /**
     * @return string|null
     */
    function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     */
    function setMimeType(string $mimeType = null): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @param int $size
     * @throws NotImplementedException
     */
    function setSize(int $size): void
    {
        throw new NotImplementedException();
    }

    /**
     * @return int
     * @throws NotImplementedException
     */
    function getSize(): int
    {
        throw new NotImplementedException();
    }
}
