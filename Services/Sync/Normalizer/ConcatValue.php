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

    public function normalize($value, $index = null)
    {
        // allows for use of template string with placeholder %
        if(strpos($this->_separator, '%') !== false) {
            $templateParts = explode('%', $this->_separator);
            $templateString = '';
            $indexedValues = array_values($value);
            foreach ($templateParts as $index => $templatePart) {
                $templateString .= $templatePart . (isset($indexedValues[$index]) ? $indexedValues[$index] : '');
            }
            return $templateString;
        }

        return is_array($value) ? implode($this->_separator, $value) : $value;
    }
}
