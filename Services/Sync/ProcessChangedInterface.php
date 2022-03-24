<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface ProcessChangedInterface extends SyncModelInterface
{
    function itemNeedsUpdate($item, $normalizedData);
}
