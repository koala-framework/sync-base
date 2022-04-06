<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged;

use Kwf_Model_Db;
use Kwf_Model_Interface;

class BasicModelKwfWrapper extends \Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicModelKwfWrapper implements ModelInterface
{
    protected $lastUpdateFieldName;

    public function __construct(Kwf_Model_Interface $model, $primaryKeyFieldNames = array(), $parentRelationFieldName = null, $lastUpdateFieldName = null, $debug = false)
    {
        parent::__construct($model, $primaryKeyFieldNames, $parentRelationFieldName, $debug);
        $this->lastUpdateFieldName = $lastUpdateFieldName;
    }

    function deleteOthers($seenItemIds, $parentItemId = null)
    {
        $select = $this->model->select();
        if (count($seenItemIds)) $select->whereNotEquals('id', $seenItemIds);
        if ($parentItemId && $this->parentRelationFieldName) {
            $select->whereEquals($this->parentRelationFieldName, $parentItemId);
        }
        $deletedIds = $this->model->getIds($select);
        $this->model->deleteRows($select);
        return $deletedIds;
    }

    function deleteOrphans($seenParentItemIds = array())
    {
        if (!$this->parentRelationFieldName) return;

        $select = $this->model->select();
        if(count($seenParentItemIds)) $select->whereNotEquals($this->parentRelationFieldName, $seenParentItemIds);
        $this->model->deleteRows($select);
    }

    function isValid($normalizedData, $parentItem = null)
    {
        Kwf_Model_Db::clearAllRows();
        return parent::isValid($normalizedData, $parentItem);
    }

    function needsUpdate($item, $normalizedData)
    {
        return $this->lastUpdateFieldName
            ? strtotime($item->{$this->lastUpdateFieldName}) < strtotime($normalizedData[$this->lastUpdateFieldName])
            : true; // always update items without lastUpdateFieldName
    }

}
