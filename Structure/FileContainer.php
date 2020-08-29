<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Structure;

/**
 * Class FileContainer
 *
 * Represents a file in memory.
 *
 * @package BastSys\CdnBundle\Structure
 * @author mirkl
 */
class FileContainer
{
    private ?string $name;
    private ?string $contentType;
    private ?string $content;
    private int $size = 0;

    /**
     * FileContainer constructor.
     * @param string|null $name
     * @param string|null $contentType
     * @param string|null $content
     */
    public function __construct(?string $name, ?string $contentType, ?string $content)
    {
        $this->name = $name;
        $this->contentType = $contentType;
        $this->content = $content;

        if($content) {
            $this->size = mb_strlen($content, '8bit');
        }
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return false|int
     */
    public function getSize()
    {
        return $this->size;
    }

}
