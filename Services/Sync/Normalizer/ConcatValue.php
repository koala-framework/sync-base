<?php
namespace Kwf\SyncBaseBundle\Services\Sync\Normalizer;

use Kwf\SyncBaseBundle\Services\Sync\NormalizerInterface;

class ConcatValue implements NormalizerInterface
{
    protected $_separator;

    public function __construct($separator = '')
    {
        $this->_separator = $separator;
    }

    public function normalize($value)
    {
        return is_array($value) ? implode($this->_separator, $value) : $value;
    }
}
