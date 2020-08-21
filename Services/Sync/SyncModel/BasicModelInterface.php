<?php
namespace Kwf\SyncBaseBundle\Services\Sync\SyncModel;

interface BasicModelInterface
{
    function getItem($normalizedData);
    function restoreItem($normalizedData);
    function createItem($normalizedData);
    function updateItem($item, $normalizedData);
    function getId($item);
}
