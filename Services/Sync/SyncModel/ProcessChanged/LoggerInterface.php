<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicLoggerInterface;

interface LoggerInterface extends BasicLoggerInterface
{
    function seenItemIds($itemIds);
    function deletedItemIds($itemIds);
}
