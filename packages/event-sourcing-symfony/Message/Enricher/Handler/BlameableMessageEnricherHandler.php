<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Security\Core\Security;
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
 *     arguments: ['@Symfony\Component\Security\Core\Security']
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
     * {@inheritdoc}
     */
    public function enrich(MessageInterface $message): MessageInterface
    {
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            return $message->withMetadata([
                self::METADATA_BLAMEABLE => [
                    'id'       => method_exists($user, 'getId') ? $user->getId() : null,
                    'username' => method_exists($user, 'getUsername') ? $user->getUsername() : null,
                    'email'    => method_exists($user, 'getEmail') ? $user->getEmail() : null,
                ],
            ]);
        }

        return $message;
    }
}
