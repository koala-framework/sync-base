<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface NormalizerInterface
{
    /**
     * @param $rawData
     * @return array
     */
    function normalize($rawData);

    /**
     * checks a normalized dataset and returns wheter it's valid.
     * @param mixed $normalizedData the normalized data to be validated.
     * @return bool
     */
    function validate($normalizedData);
}
