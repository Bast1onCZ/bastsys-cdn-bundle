<?php

namespace BastSys\CdnBundle\EventListener;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Service\IFileService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * Class FileRemoveListener
 * @package BastSys\CdnBundle\EventListener
 * @author mirkl
 */
class FileRemoveListener implements EventSubscriber
{
    /** @var IFileService */
    private $fileService;

    /**
     * FileRemoveListener constructor.
     * @param IFileService $fileService
     */
    public function __construct(IFileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preRemove
        ];
    }

    /**
     * Called whenever an entity has been removed
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof IFile && $this->fileService->hasContents($entity)) {
            $this->fileService->deleteFile($entity);
        }
    }
}
