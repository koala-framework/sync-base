<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\DeleteAllTogether;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicModelInterface;

interface ModelInterface extends BasicModelInterface
{
    function deleteOthers($itemIds);
}
