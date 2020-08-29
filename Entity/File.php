<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Entity;

use BastSys\CdnBundle\Service\IFileLinkService;
use BastSys\UtilsBundle\Entity\Identification\AUuidEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class File
 * @package BastSys\CdnBundle\Entity
 * @author  mirkl
 *
 * @ORM\Entity()
 * @ORM\Table(name="cdn__file")
 */
class File extends AUuidEntity implements IFile
{
    /**
     * @var IFileLinkService - insert through FileLinkListener
     */
    private $fileLinkService;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $mimeType = null;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $size = 0;

    public function __construct()
    {
        parent::__construct();

        $this->size = -1;
    }

    /**
     * @param IFileLinkService $fileLinkService
     */
    public function setFileLinkService(IFileLinkService $fileLinkService): void
    {
        $this->fileLinkService = $fileLinkService;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->fileLinkService->getFileLink($this);
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     */
    public function setMimeType(string $mimeType = null): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? $this->getId();
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }
}
