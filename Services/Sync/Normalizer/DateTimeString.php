<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class DateTimeString implements NormalizerInterface
{
    protected $format;

    public function __construct($format = null)
    {
        $this->format = $format;
    }

    public function normalize($value)
    {
        if (!$value) return $value;

        $timestamp = strtotime($value);
        return $this->format ? date($this->format, $timestamp) : $timestamp;
    }
}
