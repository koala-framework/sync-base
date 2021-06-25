<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use Kwf\SyncBaseBundle\Services\Sync\SyncModelInterface;

class SkipLevelAdapter implements SyncModelInterface
{
    protected $attributeNameToUse;
    protected $syncModel;
    public function __construct($attributeNameToUse, SyncModelInterface $syncModel)
    {
        $this->attributeNameToUse = $attributeNameToUse;
        $this->syncModel = $syncModel;
    }

    function updateOrCreate($rawData, $index, $parentItem = null)
    {
        $usedPartOfRawData = $rawData;
        foreach (explode(".", $this->attributeNameToUse) as $attributeName) {
            $usedPartOfRawData = $usedPartOfRawData[$attributeName];
        }

        return $this->syncModel->updateOrCreate($usedPartOfRawData, $index, $parentItem);
    }

    function commitTransaction($countItems)
    {
        $this->syncModel->commitTransaction($countItems);
    }
}
