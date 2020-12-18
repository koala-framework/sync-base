<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface SyncModelInterface
{
    function updateOrCreate($rawData, $index, $parentItem = null);
    function commitTransaction($countItems);
}
