<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class RawValueJsonAggregator implements NormalizerInterface
{
    protected $mapping;

    public function __construct($mapping)
    {
        $this->mapping = $mapping;
    }

    public function normalize($value, $index = null)
    {
        $mappedValues = array();
        foreach ($this->mapping as $key => $syncKey) {
            if (!isset($value[$syncKey])) {
                continue;
            }
            $mappedValues[$key] = $value[$syncKey];

        }
        return json_encode($mappedValues);
    }
}
