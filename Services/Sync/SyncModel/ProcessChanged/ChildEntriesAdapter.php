<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged;

use Kwf\SyncBaseBundle\Services\Sync\ProcessChangedInterface;

class ChildEntriesAdapter extends \Kwf\SyncBaseBundle\Services\Sync\SyncModel\ChildEntriesAdapter implements ProcessChangedInterface
{
    function skip($rawData, $index, $parentItem = null)
    {
        $usedPartOfRawData = $rawData;
        foreach (explode(".", $this->attributeName) as $attributeName) {
            $usedPartOfRawData = is_null($attributeName) ? $usedPartOfRawData : $usedPartOfRawData[$attributeName];
        }

        foreach ($usedPartOfRawData as $key => $item) {
            $this->syncModel->skip($item, $key, $parentItem);
        }
    }
}
