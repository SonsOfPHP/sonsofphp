<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Blamable Enricher.
 *
 * Adds User information to the event message's metadata
 *
 * <code>
 * # config/services.yaml
 * services:
 *   SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\BlameableMessageEnricherHandler:
 *     arguments: ['@Symfony\Bundle\SecurityBundle\Security']
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class BlameableMessageEnricherHandler implements MessageEnricherHandlerInterface
{
    public const METADATA_BLAMEABLE = '__user';

    public function __construct(
        private Security $security
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function enrich(MessageInterface $message): MessageInterface
    {
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            return $message->withMetadata([
                self::METADATA_BLAMEABLE => [
                    'id'         => method_exists($user, 'getId') ? $user->getId() : null,
                    'identifier' => $user->getUserIdentifier(),
                ],
            ]);
        }

        return $message;
    }
}
