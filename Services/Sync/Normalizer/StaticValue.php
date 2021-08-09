<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class StaticValue implements NormalizerInterface
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function normalize($value, $index = null)
    {
        return $this->value;
    }
}
