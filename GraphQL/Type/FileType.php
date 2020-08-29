<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\GraphQL\Type;

use BastSys\GraphQLBundle\GraphQL\ScalarType\mimeType\MimeType;
use BastSys\GraphQLBundle\GraphQL\ScalarType\UuidType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class FileType
 * @package BastSys\CdnBundle\GraphQL\Type
 * @author mirkl
 */
class FileType extends AbstractObjectType
{
    /**
     * @param \Youshido\GraphQL\Config\Object\ObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'id' => new UuidType(),
            'link' => [
                'type' => new StringType(),
                'description' => 'Link to download file contents'
            ],
            'name' => [
                'type' => new StringType(),
                'description' => 'File name'
            ],
            'mimeType' => new MimeType(),
            'size' => [
                'type' => new IntType(),
                'description' => 'Size of file in bytes'
            ]
        ]);
    }
}
