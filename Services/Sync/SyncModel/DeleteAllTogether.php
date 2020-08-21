<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeleteAllTogether\LoggerInterface;
use Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeleteAllTogether\ModelInterface;
use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class DeleteAllTogether extends BasicSyncModel
{
    /** @var ModelInterface $model */
    protected $model;
    /** @var LoggerInterface $logger */
    protected $logger;
    public function __construct(NormalizerInterface $normalizer, ModelInterface $model, $additionalSyncModels = array(), LoggerInterface $logger = null)
    {
        parent::__construct($normalizer, $model, $additionalSyncModels, $logger);
    }

    function commitTransaction($countItems)
    {
        parent::commitTransaction($countItems);
        if ($this->logger) $this->logger->seenItemIds($this->seenItemIds);
        $this->model->deleteOthers($this->seenItemIds);
        if ($this->logger) $this->logger->summary();
    }
}
