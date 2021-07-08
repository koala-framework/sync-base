<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class ConcatValue implements NormalizerInterface
{
    public function normalize($value)
    {
        return is_array($value) ? implode($value) : $value;
    }
}
