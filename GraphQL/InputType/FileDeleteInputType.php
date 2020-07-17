<?php

namespace BastSys\CdnBundle\GraphQL\InputType;

use BastSys\CdnBundle\Entity\IFile;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\GraphQLBundle\GraphQL\InputType\AInputObjectType;
use BastSys\GraphQLBundle\GraphQL\InputType\IEntityApplicable;
use Youshido\GraphQL\Type\Scalar\BooleanType;

/**
 * Class FileDeleteInputType
 * @package BastSys\CdnBundle\GraphQL\InputType
 * @author mirkl
 */
class FileDeleteInputType extends AInputObjectType implements IEntityApplicable
{
    /**
     * @param \Youshido\GraphQL\Config\Object\InputObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'id' => [
                'type' => new BooleanType(),
                'description' => 'Deletes file'
            ]
        ]);
    }

    /**
     * @param IFile $entity
     * @param GraphQLRequest $request
     */
    public function applyOnEntity($entity, GraphQLRequest $request): void
    {
        $request->processIfTrue('id', function () use ($entity, $request) {
            $fileService = $request->getContainer()->get('cdn_bundle.file_service');
            try {
                $fileService->deleteFile($entity); // silence in case file in local storage does not exist
            } catch (\Exception $ex) {
            }
        });
    }

}
