<?php

namespace BastSys\CdnBundle\Service;

use BastSys\CdnBundle\Entity\File;
use BastSys\CdnBundle\Entity\IFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

/**
 * Class UploadedFileService
 * @package BastSys\CdnBundle\Service
 * @author  mirkl
 */
class UploadedFileService implements IFileService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $cdnPath;

    /**
     * UploadedFileService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param string $cdnPath
     */
    public function __construct(EntityManagerInterface $entityManager, string $cdnPath)
    {
        $this->entityManager = $entityManager;
        $this->cdnPath = $cdnPath;
    }

    /**
     * Creates File and saves its data, persists the file entity
     *
     * @param string $fileContents
     *
     * @return IFile
     * @throws \Exception
     */
    public function createFile(string $fileContents): IFile
    {
        $this->checkDirectory($this->cdnPath);

        $file = new File();
        $size = file_put_contents(
            $this->getFilePath($file),
            $fileContents
        );
        if ($size === false) {
            throw new UploadException();
        }

        $file->setSize($size);
        $this->entityManager->persist($file);

        return $file;
    }

    /**
     * @param string $dirPath
     */
    private function checkDirectory(string $dirPath)
    {
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
    }

    /**
     * @param IFile $file
     *
     * @return string
     */
    private function getFilePath(IFile $file)
    {
        $id = $file->getId();

        return "$this->cdnPath/$id";
    }

    /**
     * @param IFile $file
     *
     * @throws FileException
     */
    public function deleteFile(IFile $file): void
    {
        $this->checkDirectory($this->cdnPath);
        $entityExists = !!$this->entityManager->find(File::class, $file->getId()); // entity might be detached or removed from EntityManager when deleting file

        if (unlink($this->getFilePath($file))) {
            if ($entityExists) {
                $file->setSize(-1);
            }
        } else {
            throw new FileException();
        }

        if ($entityExists) {
            $this->entityManager->remove($file);
        }
    }

    /**
     * @param IFile $file
     *
     * @return string|null
     */
    public function getContents(IFile $file): ?string
    {
        if (!$this->hasContents($file)) {
            return null;
        }

        $filePath = $this->getFilePath($file);
        return file_get_contents($filePath);
    }

    /**
     * @param IFile $file
     *
     * @return bool
     */
    public function hasContents(IFile $file): bool
    {
        return file_exists(
            $this->getFilePath($file)
        );
    }

    /**
     * Updates date for given file
     *
     * @param IFile $file
     * @param string $newFileContents
     *
     * @throws UploadException
     */
    public function updateFile(IFile $file, string $newFileContents): void
    {
        $this->checkDirectory($this->cdnPath);

        $newSize = file_put_contents(
            $this->getFilePath($file),
            $newFileContents
        );
        if ($newSize === false) {
            throw new UploadException();
        }

        $file->setSize($newSize);
    }
}
