<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\Security;

use BastSys\CdnBundle\Entity\IFile;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class FileDownloadVoter
 * @package BastSys\CdnBundle\Security
 * @author mirkl
 */
abstract class FileDownloadVoter extends Voter
{
    /**
     *
     */
    public const ATTRIBUTE = 'bastsys.cdn_bundle.file.download';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject)
    {
        return $attribute === self::ATTRIBUTE && $subject instanceof IFile;
    }

    /**
     * @param string $attribute
     * @param IFile $subject
     * @param TokenInterface $token
     * @return bool
     */
    abstract protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token);

}
