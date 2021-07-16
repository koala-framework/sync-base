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

    public function normalize($data)
    {
        return $this->applyNormalizationConfig(array(), $data);
    }

    protected function applyNormalizationConfig($normalizedCar, $carData)
    {
        foreach ($this->normalizationConfig->getConfig() as $dbField => $config) {
            if (is_array($config) && isset($config['class'])) {
                $normalizer = $this->getNormalizer($config);
                $upstreamValue = null;
                if (isset($config['field'])) {
                    if (is_array($config['field'])) {
                        $upstreamValue = array();
                        foreach ($config['field'] as $field) {
                            $upstreamValue[$field] = $this->getUpstreamValue(array($field), $carData);
                        }
                    } else {
                        $mapping = is_array($config['field']) ? $config['field'] : array($config['field']);
                        $upstreamValue = $this->getUpstreamValue($mapping, $carData);
                    }
                }
                $normalizedCar[$dbField] = $normalizer->normalize($upstreamValue);
                continue;
            }
            $mapping = is_array($config) ? $config : array($config);
            $normalizedCar[$dbField] = $this->getUpstreamValue($mapping, $carData);
        }
        return $normalizedCar;
    }

    protected function getNormalizer($config)
    {
        $ref = new ReflectionClass($config['class']);
        return isset($config['args'])
            ? $ref->newInstanceArgs($config['args'])
            : $ref->newInstance();
    }

    protected function getUpstreamValue($mapping, $carData)
    {
        $value = null;
        foreach ($mapping as $index => $map) {
            $map = explode('.', $map);
            $drilledCarData = $carData;
            foreach ($map as $mapKey) {
                if (!$mapKey) {
                    $value = $drilledCarData;
                }
                if (is_array($mapKey)) {
                    foreach ($mapKey as $fieldName) {
                        if (!isset($drilledCarData[$fieldName])) {
                            continue;
                        }
                        $value = $drilledCarData[$mapKey];
                    }
                    break;
                }
                if (!isset($drilledCarData[$mapKey])) {
                    continue;
                }
                if (!is_array($drilledCarData[$mapKey]) || (count($mapping) === 1 && $index + 1 === count($map))) {
                    $value = $drilledCarData[$mapKey];
                    break;
                }
                $drilledCarData = $drilledCarData[$mapKey];
            }
            if (isset($value)) {
                break;
            }
        }
        return $value;
    }
}
