<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeleteAllTogether;

class BasicModelKwfWrapper extends \Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicModelKwfWrapper implements ModelInterface
{
    function deleteOthers($itemIds)
    {
        $select = new \Kwf_Model_Select();
        $select->whereNotEquals('id', $itemIds);
        $this->model->deleteRows($select);
    }
}
