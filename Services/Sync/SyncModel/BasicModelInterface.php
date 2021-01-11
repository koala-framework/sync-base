<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

interface BasicModelInterface
{
    function getItem($normalizedData, $parentItem = null);
    function restoreItem($normalizedData, $parentItem = null);
    function createItem($normalizedData, $parentItem = null);
    function updateItem($item, $normalizedData, $parentItem = null);
    function getId($item, $parentItem = null);
}
