<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

interface BasicModelInterface
{
    function isValid($normalizedData, $parentItem = null);
    function getItem($normalizedData, $parentItem = null);
    function restoreItem($normalizedData, $parentItem = null);
    function createItem($normalizedData, $parentItem = null);
    function updateItem($item, $normalizedData, $parentItem = null);
    function getId($item, $parentItem = null);
}
