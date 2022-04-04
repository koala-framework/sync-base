<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface ProcessChangedInterface extends SyncModelInterface
{
    function skip($rawData, $index, $parentItem = null);
}
