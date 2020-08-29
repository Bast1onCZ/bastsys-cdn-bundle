<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Security;

use BastSys\CdnBundle\Structure\FileContainer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class FileUploadVoter
 * @package BastSys\CdnBundle\Security
 * @author mirkl
 */
abstract class FileUploadVoter extends Voter
{
    /**
     *
     */
    public const ATTRIBUTE = 'bastsys.cdn_bundle.file.upload';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject)
    {
        return $attribute === self::ATTRIBUTE && $subject instanceof FileContainer;
    }

    /**
     * @param string $attribute
     * @param FileContainer $subject
     * @param TokenInterface $token
     * @return bool
     */
    abstract protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token);

}
