<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

interface BasicLoggerInterface
{
    const
        LEVEL_VERBOSE = 0,
        LEVEL_DEBUG = 1,
        LEVEL_INFO = 2,
        LEVEL_WARN = 3,
        LEVEL_ERROR = 4;

    function callUpdateOrCreateForData($rawData);
    function rawDataNormalized($rawData, $data);
    function itemRestored($item, $normalizedData, $syncModel);
    function itemCreated($item, $normalizedData, $syncModel);
    function itemUpdated($item, $normalizedData, $syncModel);
    function itemSkipped($normalizedData, $syncModel);
    function callUpdateOrCreateOnAdditionalSyncModel($rawData, $syncModel, $additionalSyncModel = null);
    function callCommitTransactionOnAdditionalSyncModel($countItems, $syncModel, $additionalSyncModel = null);
    function summary();
    function log($line, $level);
    function getLogLevel();
}
