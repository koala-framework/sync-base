<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;
use Kwf\SyncBaseBundle\Services\Sync\SyncModelInterface;

abstract class BasicSyncModel implements SyncModelInterface
{
    protected $normalizer;
    protected $model;
    /** @var SyncModelInterface[] $additionalSyncModels */
    protected $additionalSyncModels;
    protected $logger;
    protected $seenItemIds = array();
    protected $debug;

    public function __construct(NormalizerInterface $normalizer, BasicModelInterface $model, $additionalSyncModels = array(), BasicLoggerInterface $logger = null, $debug = false)
    {
        $this->normalizer = $normalizer;
        $this->model = $model;
        $this->additionalSyncModels = $additionalSyncModels;
        $this->logger = $logger;
        $this->debug = $debug;
    }

    function updateOrCreate($rawData, $index, $parentItem = null)
    {
        if ($this->logger) $this->logger->callUpdateOrCreateForData($rawData);
        $normalizedData = $this->normalizer->normalize($rawData);
        if ($this->logger) $this->logger->rawDataNormalized($rawData, $normalizedData);
        if (!$this->model->isValid($normalizedData, $parentItem)){
            if ($this->logger) $this->logger->normalizedDataInvalid($rawData, $normalizedData);
            return;
        }

        $item = $this->model->getItem($normalizedData, $parentItem);
        if (!$item) {
            $item = $this->model->restoreItem($normalizedData, $parentItem);
            if ($item && $this->logger) $this->logger->itemRestored($item, $normalizedData, $this->model);
            if (!$item) {
                $item = $this->model->createItem($normalizedData, $parentItem);
                if (!$item) {
                    if ($this->logger) $this->logger->itemSkipped($normalizedData, $this->model);
                    return;
                }

                if ($this->logger) $this->logger->itemCreated($item, $normalizedData, $this->model);
            }
        } else {
            $item = $this->model->updateItem($item, $normalizedData, $parentItem);
            if (!$item) {
                if ($this->logger) $this->logger->itemSkipped($normalizedData, $this->model);
                return;
            }
            if ($this->logger) $this->logger->itemUpdated($item, $normalizedData, $this->model);
        }

        if ($this->logger) $this->logger->callUpdateOrCreateOnAdditionalSyncModel($rawData, $this->model);
        foreach ($this->additionalSyncModels as $additionalSyncModel) {
            if ($this->logger) $this->logger->callUpdateOrCreateOnAdditionalSyncModel($rawData, $this->model, $additionalSyncModel);
            $additionalSyncModel->updateOrCreate($rawData, $index, $item);
        }

        if ($item != null)
            $this->seenItemIds[] = $this->model->getId($item, $parentItem);
        return $item;
    }

    function commitTransaction($countItems)
    {
        if ($this->logger) $this->logger->callCommitTransactionOnAdditionalSyncModel($countItems, $this->model);
        foreach ($this->additionalSyncModels as $additionalSyncModel) {
            if ($this->logger) $this->logger->callCommitTransactionOnAdditionalSyncModel($countItems, $this->model, $additionalSyncModel);
            $additionalSyncModel->commitTransaction($countItems);
        }
    }
}
