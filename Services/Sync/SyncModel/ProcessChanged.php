<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;
use Kwf\SyncBaseBundle\Services\Sync\ProcessChangedInterface;
use Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged\ModelInterface;
use Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged\LoggerInterface;

class ProcessChanged extends BasicSyncModel implements ProcessChangedInterface
{
    /** @var ProcessChangedInterface[] $additionalSyncModels */
    protected $additionalSyncModels;
    /** @var LoggerInterface $logger */
    protected $logger;
    /** @var ModelInterface $model */
    protected $model;
    protected $lastParentId;
    protected $seenParentItemIds = array();
    protected $skippedParentItemIds = array();
    protected $processAdditionalSyncModels;

    public function __construct(NormalizerInterface $normalizer, ModelInterface $model, $additionalSyncModels = array(), LoggerInterface $logger = null, $debug = false)
    {
        parent::__construct($normalizer, $model, $additionalSyncModels, $logger, $debug);
        $this->additionalSyncModels = $additionalSyncModels;
    }

    function updateOrCreate($rawData, $index, $parentItem = null)
    {
        // deleteOthers will not be called for the last parentItem because $this->lastParentId != $parentItem->id will always be false
        if ($parentItem && $this->lastParentId && $this->lastParentId != $parentItem->id) {
            $deletedItemsIds = $this->model->deleteOthers($this->seenItemIds, $this->lastParentId);
            if ($this->logger) $this->logger->deletedItemIds($deletedItemsIds);
            $this->seenItemIds = array();
        }
        if ($parentItem) {
            $this->lastParentId = $parentItem->id;
            $this->seenParentItemIds[] = $parentItem->id;
        }

        if ($this->logger) $this->logger->callUpdateOrCreateForData($rawData);
        $normalizedData = $this->normalizer->normalize($rawData, $index);
        if ($this->logger) $this->logger->rawDataNormalized($rawData, $normalizedData);
        if (!$this->model->isValid($normalizedData, $parentItem)) {
            if ($this->logger) $this->logger->normalizedDataInvalid($rawData, $normalizedData);
            return;
        }

        $this->processAdditionalSyncModels = false;
        $item = $this->model->getItem($normalizedData, $parentItem);
        if (!$item) {
            $this->processAdditionalSyncModels = true;
            $item = $this->model->restoreItem($normalizedData, $parentItem);
            if ($item && $this->logger) $this->logger->itemRestored($item, $normalizedData, $this->model);
            if (!$item) {
                $item = $this->model->createItem($normalizedData, $parentItem);
                if ($this->logger) $this->logger->itemCreated($item, $normalizedData, $this->model);
            }
            $this->seenItemIds[] = $this->model->getId($item, $parentItem);
        } else {
            if ($this->model->needsUpdate($item, $normalizedData)) {
                $this->processAdditionalSyncModels = true;
                $item = $this->model->updateItem($item, $normalizedData, $parentItem);
                if ($this->logger) $this->logger->itemUpdated($item, $normalizedData, $this->model);
            } else {
                if ($this->logger) $this->logger->itemSkipped($normalizedData, $this->model);
            }
            $this->seenItemIds[] = $this->model->getId($item, $parentItem);
        }

        if ($this->logger) $this->logger->callUpdateOrCreateOnAdditionalSyncModel($rawData, $this->model);
        foreach ($this->additionalSyncModels as $additionalSyncModel) {
            if ($this->processAdditionalSyncModels) {
                if ($this->logger) $this->logger->callUpdateOrCreateOnAdditionalSyncModel($rawData, $this->model, $additionalSyncModel);
                $additionalSyncModel->updateOrCreate($rawData, $index, $item);
            } else {
                $additionalSyncModel->skip($rawData, $index, $item);
            }
        }
        return $item;
    }

    function commitTransaction($countItems)
    {
        if ($this->logger) $this->logger->callCommitTransactionOnAdditionalSyncModel($countItems, $this->model);
        foreach ($this->additionalSyncModels as $additionalSyncModel) {
            if ($this->logger) $this->logger->callCommitTransactionOnAdditionalSyncModel($countItems, $this->model, $additionalSyncModel);
            $additionalSyncModel->commitTransaction($countItems);
        }

        if ($this->hasSeenOrSkippedParentItems()) {
            if ($this->lastParentId) { // deletes not-seen relation items of lastParentItem
                $deletedItemIds = $this->model->deleteOthers($this->seenItemIds, $this->lastParentId);
                if ($this->logger) $this->logger->deletedItemIds($deletedItemIds);
            }
            $this->model->deleteOrphans(array_merge($this->seenParentItemIds, $this->skippedParentItemIds));
        } else {
            $deletedItemIds = $this->model->deleteOthers($this->seenItemIds);
            if ($this->logger) $this->logger->deletedItemIds($deletedItemIds);
        }

        if ($this->logger) $this->logger->summary();
    }

    function skip($rawData, $index, $parentItem = null)
    {
        if ($parentItem) $this->skippedParentItemIds[$parentItem->id] = $parentItem->id;
    }

    function hasSeenOrSkippedParentItems()
    {
        return count($this->seenParentItemIds) || count($this->skippedParentItemIds);
    }
}
