<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeletePerItem\LoggerInterface;
use Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeletePerItem\ModelInterface;
use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class DeletePerItem extends BasicSyncModel
{
    /** @var ModelInterface $model */
    protected $model;
    /** @var LoggerInterface $model */
    protected $logger;
    public function __construct(NormalizerInterface $normalizer, ModelInterface $model, $additionalSyncModels = array(), LoggerInterface $logger = null)
    {
        parent::__construct($normalizer, $model, $additionalSyncModels, $logger);
    }

    function commitTransaction($countItems)
    {
        parent::commitTransaction($countItems);
        foreach (array_diff($this->model->getAllIds(), $this->seenItemIds) as $idToDelete) {
            if ($this->logger) $this->logger->itemIdToDelete($idToDelete);
            $this->model->deleteItem($idToDelete);
        }
        if ($this->logger) $this->logger->summary();
    }
}
