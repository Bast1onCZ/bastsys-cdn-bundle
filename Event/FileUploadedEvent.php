<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Event;

use BastSys\CdnBundle\Entity\IFile;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class FileUploadedEvent
 * @package BastSys\CdnBundle\Event
 * @author mirkl
 */
class FileUploadedEvent extends Event
{
    /**
     * @var IFile
     */
    private $file;

    /**
     * FileUploadedEvent constructor.
     * @param IFile $file
     */
    public function __construct(IFile $file)
    {
        $this->file = $file;
    }
}
