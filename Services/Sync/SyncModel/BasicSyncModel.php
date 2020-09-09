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
    public function __construct(NormalizerInterface $normalizer, BasicModelInterface $model, $additionalSyncModels = array(), BasicLoggerInterface $logger = null)
    {
        $this->normalizer = $normalizer;
        $this->model = $model;
        $this->additionalSyncModels = $additionalSyncModels;
        $this->logger = $logger;
    }

    function validate($data)
    {
        return ($data != null);
    }

    function updateOrCreate($rawData, $index, $parentItem = null)
    {
        if ($this->logger) $this->logger->callUpdateOrCreateForData($rawData);
        $normalizedData = $this->normalizer->normalize($rawData);
        if ($this->logger) $this->logger->rawDataNormalized($rawData, $normalizedData);
        $item = $this->model->getItem($normalizedData);
        if (!$item) {
            $item = $this->model->restoreItem($normalizedData);
            if ($item && $this->logger) $this->logger->itemRestored($item, $normalizedData, $this->model);
            if (!$item) {
                $item = $this->model->createItem($normalizedData);
                if (!$item) {
                    // this really should never happen. yet, still, if it _does_ happen we're gonna log it carefully.
                    return;
                }

                if ($this->logger) $this->logger->itemCreated($item, $normalizedData, $this->model);
            }
        } else {
            $item = $this->model->updateItem($item, $normalizedData);
            if ($this->logger) $this->logger->itemUpdated($item, $normalizedData, $this->model);
        }

        if ($this->logger) $this->logger->callUpdateOrCreateOnAdditionalSyncModel($rawData, $this->model);
        foreach ($this->additionalSyncModels as $additionalSyncModel) {
            if ($this->logger) $this->logger->callUpdateOrCreateOnAdditionalSyncModel($rawData, $this->model, $additionalSyncModel);
            $additionalSyncModel->updateOrCreate($rawData, $index, $item);
        }

        $this->seenItemIds[] = $this->model->getId($item);
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
