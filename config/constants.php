<?php
// Suffix value with '*' for it to be set as the default value initially
$VERSIONS = array(
    'arrow-9.x',
    'arrow-10.0*',
    'arrow-community'
);

// Suffix value with '*' for it to be set as the default value initially
$VARIANTS = array(
    'official*',
    'beta'
);

$API_URL_CALLS = array(
    'oem_devices_list' => 'https://update.arrowos.net/api/v1/oem/devices/all/',
    'devices_version_list' => 'https://update.arrowos.net/api/v1/devices/version/all/',
    'device_info' => 'https://update.arrowos.net/api/v1/info/{device}/{variant}/{version}/{zipvariant}',
    'source_changelog' => 'https://update.arrowos.net/api/v1/source/changelog'
);
?>
