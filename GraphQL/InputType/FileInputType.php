<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\GraphQL\InputType;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\CdnBundle\Service\IFileService;
use BastSys\CdnBundle\Structure\FileContainer;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\GraphQLBundle\GraphQL\InputType\AInputObjectType;
use BastSys\GraphQLBundle\GraphQL\InputType\IEntityApplicable;
use BastSys\GraphQLBundle\GraphQL\InputType\IEntityCreatable;
use BastSys\GraphQLBundle\GraphQL\ScalarType\mimeType\MimeType;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class FileInputType
 * @package BastSys\CdnBundle\GraphQL\InputType
 * @author mirkl
 */
class FileInputType extends AInputObjectType implements IEntityApplicable, IEntityCreatable
{
    /** @var MimeType */
    private $mimeType;

    /**
     * FileInputType constructor.
     *
     * @param MimeType $mimeType
     */
    public function __construct(MimeType $mimeType = null)
    {
        if (!$mimeType) {
            $mimeType = new MimeType();
        }
        $this->mimeType = $mimeType;

        parent::__construct();
    }

    /**
     * @param GraphQLRequest $request
     *
     * @return IFile
     * @throws \Exception
     */
    function create(GraphQLRequest $request): IFile
    {
        /** @var IFileService $fileService */
        $fileService = $request->getContainer()->get('bastsys.cdn_bundle.file_service');

        $fileContainer = new FileContainer(
            $request['name'],
            $request['mimeType'],
            base64_decode($request['content'])
        );

        $entity = $fileService->createFile($fileContainer);

        return $entity;
    }

    /**
     * @param IFile $entity
     * @param GraphQLRequest $request
     * @throws \BastSys\GraphQLBundle\Exception\Process\GraphQLRequiredParameterException
     */
    public function applyOnEntity($entity, GraphQLRequest $request): void
    {
        /** @var IFileService $fileService */
        $fileService = $request->getContainer()->get('bastsys.cdn_bundle.file_service');

        $content = base64_decode($request->getParameter('content'));
        $fileService->updateFile($entity, $content);

        $request->processParameter('name', function ($value) use ($entity) {
            $entity->setName($value);
        });
        $entity->setMimeType($request->getParameter('mimeType'));
    }

    /**
     * @param ObjectTypeConfig $config
     * @throws \Youshido\GraphQL\Exception\ConfigurationException
     */
    public function build($config)
    {
        $config->addFields([
            'name' => new StringType(),
            'mimeType' => new NonNullType($this->mimeType),
            'content' => [
                'type' => new NonNullType(new StringType()),
                'description' => 'Base64 encoded file content'
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Updates or creates a file. A File does not have to possess name, but must have a mime type and contents';
    }
}
