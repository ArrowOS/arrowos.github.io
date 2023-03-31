<?php
// Each ArrowOS version should be mentioned here
$VERSIONS = array(
    'arrow-13.1',
    'arrow-13.0',
    'arrow-12.1',
    'arrow-11.0',
    'arrow-10.0',
    'arrow-9.x'
);
// Each ArrowOS variant should be mentioned here
$VARIANTS = array(
    'official',
    'experiments',
    'community',
    'community_experiments'
);

$API_URL_CALLS = array(
    'oem_devices_list' => 'https://update.arrowos.net/api/v1/oem/devices/all/',
    'devices_support_info' => 'https://update.arrowos.net/api/v1/devices/support/all/',
    'device_info' => 'https://update.arrowos.net/api/v1/info/{device}/{variant}/{version}/{zipvariant}'
);
?>
