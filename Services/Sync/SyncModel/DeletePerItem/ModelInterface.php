<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeletePerItem;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicModelInterface;

interface ModelInterface extends BasicModelInterface
{
    function deleteItem($id);
    function getAllIds();
}
