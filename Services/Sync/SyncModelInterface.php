<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface SyncModelInterface
{
    function updateOrCreate($rawData, $index, $parentItem = null);
    function commitTransaction($countItems);

    /**
     * checks a normalized dataset and returns wheter it's valid.
     * @param mixed $normalizedData the normalized data to be validated.
     * @return bool
     */
    function validate($normalizedData);
}
