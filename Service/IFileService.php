<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Service;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Structure\FileContainer;

/**
 * Interface IFileService
 * @package BastSys\CdnBundle\Service
 * @author mirkl
 */
interface IFileService
{
    /**
     * Created a persistent file from a file container
     *
     * @param FileContainer $fileContainer
     * @return IFile
     */
    function createFile(FileContainer $fileContainer): IFile;

    /**
     * Updates file
     *
     * @param IFile $file
     * @param string $newFileContents
     */
    function updateFile(IFile $file, string $newFileContents): void;

    /**
     * Deletes file
     *
     * @param IFile $file
     */
    function deleteFile(IFile $file): void;

    /**
     * Checks whether file has contents
     *
     * @param IFile $file
     *
     * @return bool
     */
    function hasContents(IFile $file): bool;

    /**
     * Gets file contents by IFile
     *
     * @param IFile $file
     *
     * @return string
     */
    function getContents(IFile $file): ?string;
}
