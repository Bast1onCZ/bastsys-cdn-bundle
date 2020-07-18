<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Event;

use BastSys\CdnBundle\Entity\IFile;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class FileUploadEvent
 * @package BastSys\CdnBundle\Event
 * @author mirkl
 */
class FileUploadEvent extends Event
{
    /**
     *
     */
    const NAME = 'cdn.file.upload';

    /**
     * @var IFile
     */
    private $file;

    /**
     * FileUploadEvent constructor.
     * @param IFile $file
     */
    public function __construct(IFile $file)
    {
        $this->file = $file;
    }
}
