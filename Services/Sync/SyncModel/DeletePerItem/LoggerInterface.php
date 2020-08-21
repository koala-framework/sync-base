<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeletePerItem;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicLoggerInterface;

interface LoggerInterface extends BasicLoggerInterface
{
    function itemIdToDelete($itemId);
}
