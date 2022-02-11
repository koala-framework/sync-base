<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface ProcessChangedInterface extends SyncModelInterface
{
    function addSkippedParentItemId($parentId);
}
