<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeleteAllTogether;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicLoggerInterface;

interface LoggerInterface extends BasicLoggerInterface
{
    function seenItemIds($itemIds);
}
