<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use Kwf\SyncBaseBundle\Services\Sync\SyncModelInterface;
use Kwf\SyncBaseBundle\Services\Sync\LoggerInterface;

class ChildEntriesAdapter implements SyncModelInterface
{
    protected $attributeName;
    protected $syncModel;
    protected $logger;

    protected $countItems = 0;

    public function __construct($attributeName, SyncModelInterface $syncModel, LoggerInterface $logger = null)
    {
        $this->attributeName = $attributeName;
        $this->syncModel = $syncModel;
        $this->logger = $logger;
    }

    function updateOrCreate($rawData, $index, $parentItem = null)
    {
        $usedPartOfRawData = $rawData;
        foreach (explode(".", $this->attributeName) as $attributeName) {
            $usedPartOfRawData = $usedPartOfRawData[$attributeName];
        }

        $index = 0;
        foreach ($usedPartOfRawData as $item) {
            if ($this->logger) $this->logger->processItem($item, $index, $this->syncModel);
            $this->syncModel->updateOrCreate($item, $index, $parentItem);
            $index++;
        }
        $this->countItems += $index;
    }

    function commitTransaction($countItems)
    {
        if ($this->logger) $this->logger->commitTransaction($this->countItems, $this->syncModel);
        $this->syncModel->commitTransaction($this->countItems);
        if ($this->logger) $this->logger->finishedSync();
    }
}
