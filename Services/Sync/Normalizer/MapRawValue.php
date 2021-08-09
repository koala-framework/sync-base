<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class MapRawValue implements NormalizerInterface
{
    protected $valueMap;
    protected $defaultValue;

    /**
     * @param array $valueMap
     * @param $defaultValue
     */
    public function __construct($valueMap, $defaultValue = null)
    {
        $this->valueMap = $valueMap;
        $this->defaultValue = $defaultValue;
    }

    public function normalize($value, $index = null)
    {
        $value = isset($this->valueMap[$value]) ? $this->valueMap[$value] : null;
        if (!$value && $this->defaultValue) {
            $value = $this->defaultValue;
        }
        return $value;
    }
}
