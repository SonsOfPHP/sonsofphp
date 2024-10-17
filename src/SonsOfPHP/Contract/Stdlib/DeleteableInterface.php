<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface DeleteableInterface
{
    /**
     * Marks the object as deleted
     */
    public function delete(): void;

    /**
     * If the object has been deleted, this will clear the timestamp of when
     * the object was deleted.
     */
    public function undelete(): void;

    /**
     * Returns true if the object has been deleted.
     */
    public function isDeleted(): bool;

    /**
     * Returns the timestamp of when the object was deleted
     *
     * This MUST return null if the object has not been deleted
     */
    public function getDeletedAt(): ?\DateTimeInterface;

    /**
     * To delete the object, a DateTime MUST be passed in. This timestamp could
     * be "now", past, or future. The constraint is left up to the implemnting
     * library.
     *
     * The object MUST be valid until the timestamp specificed. If the
     * timestamp is for the future, the object MUST remain in a "not deleted"
     * state.
     */
    public function setDeletedAt(?\DateTimeInterface $deletedAt): void;
}
