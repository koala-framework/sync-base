# sync-base

## Normalizers

#### ConfigurableNormalizer
A configurable normalizer can be used to normalize the entire structure of the raw data. A normalization config has to be passed to its constructor.
* Each config key represents the name of the normalized field (output)
* Each config value represents the name of a key in the raw data object (input). Nested values can be retrieved by separating multiple nested keys with a period (.)
* Values of the raw data object can be prioritized by grouping them in an array. The first value will have the highest priority. If that value does not exist in the raw data object, the next value will be used, and so on.
```
$normalizationConfig = array(
    // one to one mapping:
    'normalizedFieldName1' => 'rawDataFieldName',
    // nested mapping:
    'normalizedFieldName2' => 'nested.rawDataFieldName',
    // prioritized mappding:
    'normalizedFieldName2' => 'array(
        'rawDataFieldName',
        'alternativeRawDataFieldName',
        'nested.alternative.rawDataFieldName'
    )
);
```
A configurable normalizer can include configurations for other normalizers. Such a can contain three parameters:
* **field**: the key in the raw data object (can also be nested or omitted, if `StaticValue`-Normalizer)
* **class**: the class of the normalizer 
* **args**: arguments that will be passed to the normalizer's constructor

#### Callable
Provided as a function within the config, it will be called and passed all car data when normalizing - expected to return the normalized value.
```
$config = array(
    'dealer_id' => function($data) {
        return explode($data['fullId'], '.')[0];
    });
```
This example would work like a charm on a dataset that has many individual requirements.

#### MapRawValue
This normalizer maps one or multiple values from the raw data object to a specified normalized value. Here's an example of how a `MapSyncedValue`-Normalizer can be passed to the config of the `ConfigurableNormalizer`:
```
$normalizationConfig = array(
    'normalizedFieldName' => array(
        'class' => 'Kwf\SyncBaseBundle\Services\Sync\Normalizer\MapRawValue',
        'field' => 'nested.rawDataFieldName',
        'args' => array(array(
            'rawValue' => 'normalizedValue',
            'Allrad' => 'A',
            'Allradantrieb' => 'A',
            'Allrad permanent' => 'A',
            'Allrad zuschaltbar' => 'A',
            'Allrad allgemein' => 'A',
            'Front' => 'F',
            'Frontantrieb' => 'F',
            'Vorderrad' => 'F',
            'Vorderradantrieb' => 'F',
            'Hinterrad' => 'H',
            'Hinterradantrieb' => 'H',
            'Heckantrieb' => 'H',
            'Heck' => 'H'
        ))
    )
);
``` 

#### StaticValue
This normalizer sets a static value that doesn't come from the raw data object, but should be included in the normalized data object.
```
$normalizationConfig = array(
   'normalizedFieldName' => array(
       'class' => 'Kwf\SyncBaseBundle\Services\Sync\Normalizer\StaticValue',
       'args' => array(
           'staticValue'
       )
   )
);
```

#### RawValueJsonAggregator
This normalizer converts multiple values from the raw data object into a JSON string that will be assigned to a single normalized field. The **field** contains an array of raw-data-field-names, while **args** contains a mapping-array with its keys being the keys in the normalized JSON-Object, and the values being the same raw-data-fields that have been passed to the **field**. 
```
$normalizationConfig = array(
   'normalizedFieldName' => array(
       'class' => 'Kwf\SyncBaseBundle\Services\Sync\Normalizer\RawValueJsonAggregator',
       'field' => array(
            'nested.rawDataFieldName',
            'rawDataFieldName',
            'another.nested.rawDataFieldName'
        )
       'args' => array(array(
           'keyForRawDataFieldName' => 'nested.rawDataFieldName',
           'anotherKeyInTheJsonObject' => 'rawDataFieldName',
           'yetAnotherKey' => 'another.nested.rawDataFieldName'
       ))
   )
);
```
 
