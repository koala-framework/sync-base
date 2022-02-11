<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged;

use Kwf\SyncBaseBundle\Services\Sync\ProcessChangedInterface;

class ChildEntriesAdapter extends \Kwf\SyncBaseBundle\Services\Sync\SyncModel\ChildEntriesAdapter implements ProcessChangedInterface
{
    public function addSkippedParentItemId($parentId)
    {
        $this->syncModel->addSkippedParentItemId($parentId);
    }
}
