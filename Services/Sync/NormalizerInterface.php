<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

interface NormalizerInterface
{
    /**
     * @param $rawData
     * @return array
     */
    function normalize($rawData, $index = null);
}
