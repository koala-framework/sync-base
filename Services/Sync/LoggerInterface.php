<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface LoggerInterface
{
    function startSync();
    function setItemCount($count);
    function processItem($rawData, $index, $syncModel = null);
    function commitTransaction($countItems, $syncModel = null);
    function finishedSync();
}
