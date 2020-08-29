<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Model\File;

use BastSys\CdnBundle\Entity\File;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class FileRepository
 * @package BastSys\CdnBundle\Model\File
 * @author mirkl
 */
class FileRepository extends AEntityRepository
{
    /**
     * FileRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(File::class, $entityManager);
    }
}
