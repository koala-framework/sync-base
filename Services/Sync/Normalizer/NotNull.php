<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class NotNull implements NormalizerInterface
{
    protected $fallbackValue;

    public function __construct($fallbackValue)
    {
        $this->fallbackValue = $fallbackValue;
    }

    public function normalize($value)
    {
        return $value === null ? $this->fallbackValue : $value;
    }
}
