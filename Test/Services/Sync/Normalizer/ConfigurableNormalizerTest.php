<?php

namespace Kwf\SyncBaseBundle\Test\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\Normalizer\ConfigurableNormalizer;
use Kwf\SyncBaseBundle\Test\Services\Sync\Normalizer\TestNormalizationConfigImpl;
use PHPUnit\Framework\TestCase;

class ConfigurableNormalizerTest extends TestCase
{
    private $testData = array(
        0 => array(
            'id' => 00,
            'name' => 'firstItem',
            'location' => 'earth',
            'sub' => array(
                'id' => 1234,
                'name' => 'firstItemSub'
            )
        ),
        12 => array(
            'id' => 12,
            'name' => 'anotherItem',
            'location' => 'jupiterOrbit',
            'sub' => array(
                'id' => 2345,
                'name' => 'I love Space!'
            )
        ),
        20 => array(
            'id' => 20,
            'name' => 'yetAnotherItem',
            'location' => 'luna',
            'sub' => array(
                'id' => 3456,
                'name' => 'yetAnotherSubName'
            )
        ),
        24 => array(
            'id' => 24,
            'name' => 'we\'reNotDoneYet',
            'location' => 'mars',
            'sub' => array(
                'id' => 4567,
                'name' => 'still, sub name.'
            )
        ),
        36 => array(
            'id' => 42,
            'name' => 'possiblyInvalidItem',
            'location' => 827,
            'sub' => array(
                'name' => 'invalidItemSubName'
            )
        )
    );

    public function testNormalize()
    {
        //  Config in use:
        //  array(
        //      'id' => 'id',
        //      'subId' => 'sub.id',
        //      'normalizedName' => 'name',
        //      'normalizedSubName' => 'sub.name'
        //  );

        $config = new TestNormalizationConfigImpl();
        $cfgArray = $config->getConfig();
        $normalizer = new ConfigurableNormalizer($config);
        $normalizedData = array();
        foreach ($this->testData as $index => $rawItem) {
            $normalizedData[$index] = $normalizer->normalize($rawItem);
        }

        $this->assertSame($normalizedData[0]['normalizedName'], $this->testData[0][$cfgArray['normalizedName']]);
        $this->assertSame($normalizedData[24]['subId'], $this->testData[24]['sub']['id']);
        $this->assertNull($normalizedData[36]['subId']);
    }
}