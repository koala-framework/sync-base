<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged;

use Kwf_Model_Db;
use Kwf_Model_Select;

class BasicModelKwfWrapper extends \Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicModelKwfWrapper implements ModelInterface
{
    function getParentId($item)
    {
        return $this->parentRelationFieldName ? $item->{$this->parentRelationFieldName} : null;
    }

    function deleteOthers($seenItemIds, $skippedParentItemIds = array())
    {
        $select = new Kwf_Model_Select();
        if (count($seenItemIds)) $select->whereNotEquals('id', $seenItemIds);
        if ($this->parentRelationFieldName && count($skippedParentItemIds)) {
            $select->whereNotEquals($this->parentRelationFieldName, $skippedParentItemIds);
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
