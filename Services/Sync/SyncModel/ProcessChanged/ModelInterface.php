<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel\ProcessChanged;

use Kwf\SyncBaseBundle\Services\Sync\SyncModel\BasicModelInterface;

interface ModelInterface extends BasicModelInterface
{
    function deleteOthers($seenItemIds, $parentItemId);
    function deleteOrphans($seenParentItemIds);
    function needsUpdate($item, $normalizedData);
}
