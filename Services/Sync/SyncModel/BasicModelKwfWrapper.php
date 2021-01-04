<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

use \Kwf_Model_Interface;

abstract class BasicModelKwfWrapper implements BasicModelInterface
{
    protected $model;
    protected $parentRelationFieldName;
    protected $primaryKeyFieldNames; // can be a composite primary key
    protected $debug;

    public function __construct(Kwf_Model_Interface $model, $primaryKeyFieldNames = array(), $parentRelationFieldName = null, $debug = false)
    {
        $this->model = $model;
        $this->primaryKeyFieldNames = $primaryKeyFieldNames;
        $this->parentRelationFieldName = $parentRelationFieldName;
        $this->debug = $debug;
    }

    function isValid($normalizedData, $parentItem = null)
    {
        foreach ($this->primaryKeyFieldNames as $fieldName) {
            if (!isset($normalizedData[$fieldName]) || !$normalizedData[$fieldName]) return false;
        }
        return true;
    }

    function getItem($normalizedData, $parentItem = null)
    {
        return $this->model->getRow($this->getSelect($normalizedData, $parentItem));
    }

    function updateItem($item, $normalizedData, $parentItem = null)
    {
        $needsSave = false;
        foreach ($normalizedData as $key => $value) {
            if ($item->{$key} == $value) continue;
            $item->{$key} = $value;
            $needsSave = true;
        }
        if ($this->parentRelationFieldName && $item->{$this->parentRelationFieldName} != $parentItem->id) {
            $item->{$this->parentRelationFieldName} = $parentItem->id;
            $needsSave = true;
        }
        if ($needsSave) $item->save();
        return $item;
    }

    function getId($item, $parentItem = null)
    {
        return $item->{$this->model->getPrimaryKey()};
    }

    function createItem($normalizedData, $parentItem = null)
    {
        $row = $this->model->createRow($normalizedData);
        if ($this->parentRelationFieldName) {
            $row->{$this->parentRelationFieldName} = $parentItem->id;
        }
        $row->save();
        return $row;
    }

    function restoreItem($normalizedData, $parentItem = null)
    {
        if (!$this->model->hasDeletedFlag()) return null;

        $select = $this->getSelect($normalizedData, $parentItem);
        $select->ignoreDeleted(true);
        $row = $this->model->getRow($select);
        if (!$row) return null;

        $row->deleted = false;
        return $row;
    }

    protected function getSelect($normalizedData, $parentItem = null)
    {
        $select = new \Kwf_Model_Select();
        if ($this->parentRelationFieldName) {
            $select->whereEquals($this->parentRelationFieldName, $parentItem->id); // TODO konfigurierbar
        }
        foreach ($this->primaryKeyFieldNames as $key) {
            $select->whereEquals($key, $normalizedData[$key]);
        }
        return $select;
    }
}
