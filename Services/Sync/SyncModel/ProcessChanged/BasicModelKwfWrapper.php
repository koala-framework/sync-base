<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged;

use Kwf_Model_Db;
use Kwf_Model_Select;

class BasicModelKwfWrapper extends \Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicModelKwfWrapper implements ModelInterface
{
    function deleteOthers($seenItemIds, $parentItemId = null)
    {
        $select = new Kwf_Model_Select();
        if (count($seenItemIds)) $select->whereNotEquals('id', $seenItemIds);
        if ($parentItemId && $this->parentRelationFieldName) {
            $select->whereEquals($this->parentRelationFieldName, $parentItemId);
        }
        $deletedIds = $this->model->getIds($select);
        $this->model->deleteRows($select);
        return $deletedIds;
    }

    function isValid($normalizedData, $parentItem = null)
    {
        Kwf_Model_Db::clearAllRows();
        return parent::isValid($normalizedData, $parentItem);
    }
}
