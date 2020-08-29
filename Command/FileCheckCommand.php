<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Command;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Model\File\FileRepository;
use BastSys\CdnBundle\Service\IFileService;
use Doctrine\ORM\EntityManager;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @CronJob("0 0 * * 5")
 *
 * Class FileCheckCommand
 * @package BastSys\CdnBundle\Command
 * @author mirkl
 */
class FileCheckCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'cdn:file:check';

    /** @var EntityManager */
    private $em;
    /** @var FileRepository */
    private $fileRepository;
    /** @var IFileService */
    private $fileService;

    /**
     * FileCheckCommand constructor.
     * @param EntityManager $entityManager
     * @param FileRepository $fileRepository
     * @param IFileService $fileService
     */
    public function __construct(EntityManager $entityManager, FileRepository $fileRepository, IFileService $fileService)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->fileRepository = $fileRepository;
        $this->fileService = $fileService;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Deletes file entities that do not have uploaded files');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Beginning file check ...');

        /** @var IFile[] $files */
        $files = $this->fileRepository->getObjectRepository()->findAll();
        $removedFileIds = [];

        foreach ($files as $file) {
            if (!$this->fileService->hasContents($file)) {
                $this->em->remove($file);
                $removedFileIds[] = $file->getId();
            }
        }

        $removedCount = count($removedFileIds);
        if ($removedCount) {
            $output->writeln("Starting removal of ($removedCount) file entities ...");
            $this->em->flush();
            $output->writeln("Removed files: \n" . join(', ', $removedFileIds));
        } else {
            $output->writeln('All files are OK');
        }

        $totalCount = count($files);
        $output->writeln("Checked total of ($totalCount) file entities");

        return 0;
    }
}
