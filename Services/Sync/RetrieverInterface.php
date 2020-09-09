<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

use \IteratorAggregate;

interface RetrieverInterface extends IteratorAggregate
{
    /**
     * Meant to download all needed data to be fed into the iterator.
     * It is recommended to use getDataPath() for a consistent and predictable behaviour.
     * Returns whether this was a succesful endeavour.
     * @return bool
     */
    function downloadData();

    /**
     * Use this function to determine all keys you'd like to read and use $this->iterator->append($key); to feed the iterator with a key.
     * @return void
     */
    function readKeys();

    /**
     * Should return the path that the current sync will be stored within.
     * example: './17-03-2020/syncName/'.
     * this should also be used to store and retrieve data.
     * @return string
     */
    function getDataPath();

    /**
     * This function will be called when the iterator reads its current item.
     * @param mixed $key
     * @return mixed
     */
    function readItem($key);
}
