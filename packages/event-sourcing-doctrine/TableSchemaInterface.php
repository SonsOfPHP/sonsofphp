<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\EventSourcing;

/**
 * Table Schema Interface.
 *
 * Because each implementation is different, we need a way to define how we
 * want to use a database table. Using this interface we can define where
 * and how we want to store the information.
 *
 * NOTE: In the future, the table schema can be used to create the table if it
 *       does not exist. Migrations should be done by hand
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TableSchemaInterface
{
    /**
     * Returns the table name where events are stored.
     */
    public function getTableName(): string;

    /**
     * Returns the column name for where the Aggregate ID is kept.
     */
    public function getAggregateIdColumn(): string;

    /**
     * Returns the column name for where the Aggregate Version is kept.
     */
    public function getAggregateVersionColumn(): string;

    /**
     * Returns a key => value array. Keys are the column names and the values
     * are the column types.
     *
     * NOTE: The returned order of the columns should match the same order as the
     *       mapping
     */
    public function getColumns(): array;

    /**
     * After a MessageInterface is serialized, it will use this to map the
     * event data to the correct database columns.
     *
     * NOTE: The returned order should match the same order as the getColumns
     */
    public function mapEventDataToColumns(array $data): array;

    /**
     * When retrieving rows from the database, the row will be passed in here. The
     * output of this will then be deserialized using the MessageSerializer.
     */
    public function mapColumnsToEventData(array $result): array;
}
