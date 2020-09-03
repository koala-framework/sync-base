<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicSyncModel;

interface LoggerInterface
{
    /**
     * Called when the sync starts, before anything else is.
     * @return void
     */
    function startSync();

    /**
     * Sets, if a countable iterator is given, its count.
     * @param integer $count
     * @return void
     */
    function setItemCount($count);

    /**
     * Is being called for every syncModel that was provided for this sync.
     * If sync model is not provided, it's the initial call for every item that can
     * be used like a "beforeProcessItem".
     * @param $rawData array
     * @param $index integer
     * @param $syncModel BasicSyncModel
     * @return void
     */
    function processItem($rawData, $index, $syncModel = null);

    /**
     * @param integer $countItems
     * @param BasicSyncModel $syncModel
     * @return void
     */
    function commitTransaction($countItems, $syncModel = null);

    /**
     * Is called at the very end of the sync, after everything else has finished.
     * @return void
     */
    function finishedSync();
}
