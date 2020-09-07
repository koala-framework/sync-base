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
     * Meant to read locally stored data.
     * It is recommended to use getDataPath() for a consistent and predictable behaviour.
     * Will return an array that is then fed into the iterator, as a logical per-item summary -
     * but can be laid out as needed.
     * @return array
     */
    function readData();

    /**
     * Should return the path that the current sync will be stored within.
     * example: './17-03-2020/syncName/'.
     * this should also be used to store and retrieve data.
     * @return string
     */
    function getDataPath();
}
