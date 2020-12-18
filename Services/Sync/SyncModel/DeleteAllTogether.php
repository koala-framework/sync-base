<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeleteAllTogether\LoggerInterface;
use Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeleteAllTogether\ModelInterface;
use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class DeleteAllTogether extends BasicSyncModel
{
    function commitTransaction($countItems)
    {
        parent::commitTransaction($countItems);
        if ($this->logger) $this->logger->seenItemIds($this->seenItemIds);
        $this->model->deleteOthers($this->seenItemIds);
        if ($this->logger) $this->logger->summary();
    }
}
