<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

/**
 * Both Command and Queries implement the MessageInterface
 *
 * A Message MUST be:
 *   - Immutable
 *   - Able to be serialized. The Message may be processed asynchronously and
 *     will need to be serialized.
 *
 * Serializing the message can be done any number of ways and should be left
 * up to the developer to implement.
 *
 * Message may MOST be handled once.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageInterface// extends \JsonSerializable, \Serializable
{
    /**
     * Adds a new value for a given key.
     *
     * If a value already exists, this will overwrite that value.
     *
     * If the value for key exists, and the values are equal, this should
     * not return a new object.
     *
     * If the $key is an array, it should be a key => value array and will overwrite
     * all the existing values
     *
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     *   - If the given key or value is invalid
     *
     * Examples:
     *   $message = $message->with('user_id', 42);
     *   $message = $message->with([
     *      'user_id'    => 42,
     *      'account_id' => 2131,
     *   ]);
     */
    //public function with(string|array $key, mixed $value): static;

    /**
     * Returns the value stored for a given key.
     *
     * If no key is passed in or if null is passed in, it will return
     * all the values
     *
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface
     *   - If the given key is not part of this message
     *
     * Examples:
     *   $userId  = $message->get('user_id');
     *   $payload = $message->get();
     */
    //public function get(?string $key = null): mixed;
}
