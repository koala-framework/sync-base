<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizationConfigInterface;
use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;
use ReflectionClass;

class ConfigurableNormalizer implements NormalizerInterface
{
    /**
     * @var NormalizationConfigInterface
     */
    protected $normalizationConfig;

    public function __construct(NormalizationConfigInterface $normalizationConfig)
    {
        $this->normalizationConfig = $normalizationConfig;
    }

    public function normalize($data, $index = null)
    {
        return $this->applyNormalizationConfig(array(), $data, $index);
    }

    protected function applyNormalizationConfig($normalizedData, $data, $index)
    {
        foreach ($this->normalizationConfig->getConfig() as $dbField => $config) {
            if (is_array($config) && isset($config['class'])) {
                $normalizer = $this->getNormalizer($config);
                $upstreamValue = null;
                if (isset($config['field'])) {
                    if (is_array($config['field'])) {
                        $upstreamValue = array();
                        foreach ($config['field'] as $field) {
                            $upstreamValue[$field] = $this->getUpstreamValue(array($field), $data);
                        }
                    } else {
                        $mapping = is_array($config['field']) ? $config['field'] : array($config['field']);
                        $upstreamValue = $this->getUpstreamValue($mapping, $data);
                    }
                }
                $normalizedData[$dbField] = $normalizer->normalize($upstreamValue, $index);
                continue;
            }
            $mapping = is_array($config) ? $config : array($config);
            $normalizedData[$dbField] = $this->getUpstreamValue($mapping, $data);
        }
        return $normalizedData;
    }

    protected function getNormalizer($config)
    {
        $ref = new ReflectionClass($config['class']);
        return isset($config['args'])
            ? $ref->newInstanceArgs($config['args'])
            : $ref->newInstance();
    }

    protected function getUpstreamValue($mapping, $data)
    {
        $value = null;
        foreach ($mapping as $index => $map) {
            $map = explode('.', $map);
            $drilledData = $data;
            foreach ($map as $mapKey) {
                if (!$mapKey) {
                    $value = $drilledData;
                }
                if (is_array($mapKey)) {
                    foreach ($mapKey as $fieldName) {
                        if (!isset($drilledData[$fieldName])) {
                            continue;
                        }
                        $value = $drilledData[$mapKey];
                    }
                    break;
                }
                if (!isset($drilledData[$mapKey])) {
                    break;
                }
                if (!is_array($drilledData[$mapKey]) || (count($mapping) === 1 && $index + 1 === count($map))) {
                    $value = $drilledData[$mapKey];
                    break;
                }
                $drilledData = $drilledData[$mapKey];
            }
            if (isset($value)) {
                break;
            }
        }
        return $value;
    }
}
