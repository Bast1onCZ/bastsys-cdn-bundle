<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\EventListener;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Service\IFileLinkService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 *
 * Class FileLinkListener
 *
 * Inserts link to every loaded IFile on postLoad
 *
 * @package BastSys\CdnBundle\EventListener
 */
class FileLinkListener implements EventSubscriber
{
    /** @var IFileLinkService */
    private $fileLinkService;

    /**
     * FileLinkListener constructor.
     *
     * @param IFileLinkService $fileLinkService
     */
    public function __construct(IFileLinkService $fileLinkService)
    {
        $this->fileLinkService = $fileLinkService;
    }

    /**
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
            Events::prePersist,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->handleLinkInsert($args);
    }

    /**
     * Inserts the file link to Entity given by LifecycleEventArgs in case it implements IFile
     *
     * @param LifecycleEventArgs $args
     */
    private function handleLinkInsert(LifecycleEventArgs $args)
    {
        $file = $args->getEntity();
        if (!$file instanceof IFile) {
            return;
        }

        $file->setFileLinkService($this->fileLinkService);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->handleLinkInsert($args);
    }
}
