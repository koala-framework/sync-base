<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

interface BasicLoggerInterface
{
    function callUpdateOrCreateForData($rawData);
    function rawDataNormalized($rawData, $data);
    function itemRestored($item, $normalizedData, $syncModel);
    function itemCreated($item, $normalizedData, $syncModel);
    function itemUpdated($item, $normalizedData, $syncModel);
    function callUpdateOrCreateOnAdditionalSyncModel($rawData, $syncModel, $additionalSyncModel = null);
    function callCommitTransactionOnAdditionalSyncModel($countItems, $syncModel, $additionalSyncModel = null);
    function summary();
}
