<?php
$defaultLength = 8;

$app->on('admin.init', function () {
  $this->helper('admin')->addAssets('cockpituniqueidfield:assets/field-uniqueid.tag');
});

/*
 * Initialize uniqueid fields
 */
$app->on("collections.save.before", function ($collectionName, &$entry, $isUpdate) use ($app, $defaultLength) {
  $collection = $app->module('collections')->collection($collectionName);
  foreach ($collection['fields'] as $field) {
    $fieldName = $field['name'];
    if ($field['type'] === 'uniqueid') {
      if (!$entry[$fieldName] || !$isUpdate) {
        $length = is_int($field['options']['length']) ? $field['options']['length'] : $defaultLength;
        $entry[$fieldName] = unusedUniqId($app, $collectionName, $fieldName, $length);
      }
    } else if ($field['type'] === 'repeater') {
      $repeaterEntries = &$entry[$fieldName];
      foreach ($repeaterEntries as &$repeaterEntry) {
        if ($repeaterEntry['field']['type'] === "set" || $field['options']['field']['type'] === "set") {
          $setFields = $repeaterEntry['field']['options']['fields'] ?: $field['options']['field']['options']['fields'];
          foreach ($setFields as $setField) {
            if ($setField['type'] === "uniqueid") {
              $setFieldName = $setField['name'];
              if (!$repeaterEntry['value'][$setFieldName] || !$isUpdate) {
                $length = $defaultLength;
                if ($setField['options'] && is_int($setField['options']['length'])) {
                  $length = $setField['options']['length'];
                }
                $repeaterEntry['value'][$setFieldName] = unusedUniqId($app, repeaterSetFieldValues($repeaterEntries, $setFieldName), null, $length);
              }
            }
          }
        }
      }
    }
  }
});

function repeaterSetFieldValues($repeaterEntries, $setFieldName)
{
  $repeaterEntriesValues = [];
  foreach ($repeaterEntries as $repeaterEntry) {
    $repeaterEntriesValues[] = $repeaterEntry['value'];
  }
  $repeaterEntriesSetValues = [];
  foreach ($repeaterEntriesValues as $setValues) {
    foreach ($setValues as $key => $val) {
      $repeaterEntriesSetValues[$key][] = $val;
    }
  }
  return $repeaterEntriesSetValues[$setFieldName];
}

/*
 * Generate unused uniqId using betterUniqId
 */
function unusedUniqId($app, $uniqueAcross, $fieldName, $length)
{
  $uniqId = null;
  if (is_string($uniqueAcross) && is_string($fieldName)) {
    $collectionName = $uniqueAcross;
    do {
      $uniqId = betterUniqId($length);
      $criteria = [
        $fieldName => $uniqId
      ];
      $exists = $app->module('collections')->count($collectionName, $criteria) > 0;
    } while ($exists);
  } else if (is_array($uniqueAcross)) {
    do {
      $uniqId = betterUniqId($length);
    } while (in_array($uniqId, $uniqueAcross));
  }
  return $uniqId;
}

/*
 * Generates a uniqId not dependant on current time
 */
function betterUniqId($length)
{
  if (function_exists("random_bytes")) {
    $bytes = random_bytes(ceil($length / 2));
  } elseif (function_exists("openssl_random_pseudo_bytes")) {
    $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
  } else {
    throw new Exception("no cryptographically secure random function available");
  }
  return substr(bin2hex($bytes), 0, $length);
}