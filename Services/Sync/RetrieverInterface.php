<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

use \IteratorAggregate;

interface RetrieverInterface extends IteratorAggregate
{
    /**
     * Meant to download all needed data to be fed into the iterator.
     */
    function prepare();
}
