<?php

$app->on('admin.init', function () {
  $this->helper('admin')->addAssets('cockpituniqueidfield:assets/field-uniqueid.tag');
});

/*
 * Initialize uniqueid fields
 */
$app->on("collections.save.before", function ($collectionName, &$entry, $isUpdate) use ($app) {
  $collection = $app->module('collections')->collection($collectionName);
  foreach ($collection['fields'] as $field) {
    if ($field['type'] == 'uniqueid') {
      $fieldName = $field['name'];
      if (!$entry[$fieldName]) {
        $entry[$fieldName] = unusedUniqId($app, $collectionName, $fieldName);
      }
    }
  }
});

/*
 * Generate unused uniqId using uniqidReal
 */
function unusedUniqId($app, $collectionName, $fieldName) {
  do {
    $uniqId = betterUniqId();
    $criteria = [
      $fieldName => $uniqId
    ];
    $exists = $app->module('collections')->count($collectionName, $criteria) > 0;
  } while ($exists);
  return $uniqId;
}

/*
 * Generates a uniqId not dependant on current time
 */
function betterUniqId($length = 8) {
  if (function_exists("random_bytes")) {
    $bytes = random_bytes(ceil($length / 2));
  } elseif (function_exists("openssl_random_pseudo_bytes")) {
    $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
  } else {
    throw new Exception("no cryptographically secure random function available");
  }
  return substr(bin2hex($bytes), 0, $length);
}