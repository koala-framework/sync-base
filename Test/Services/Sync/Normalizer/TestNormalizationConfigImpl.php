<?php

namespace Kwf\SyncBaseBundle\Test\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizationConfigInterface;
use PHPUnit\Framework\TestCase;

class TestNormalizationConfigImpl implements NormalizationConfigInterface
{
    public function getConfig()
    {
        return array(
            'id' => 'id',
            'subId' => 'sub.id',
            'normalizedName' => 'name',
            'normalizedSubName' => 'sub.name'
            // extend me
        );
    }
}