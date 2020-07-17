<?php

namespace BastSys\CdnBundle\Service;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Structure\VirtualFile;

interface IFileService
{
    /**
     * Saves file contents and returns IFile that can be used to access it
     *
     * @param string $fileContents
     *
     * @return IFile
     */
    function createFile(string $fileContents): IFile;

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

    /**
     * Loads necessary information to a virtual file.
     *
     * @param VirtualFile $virtualFile
     */
    function feedVirtualFile(VirtualFile $virtualFile): void;
}
