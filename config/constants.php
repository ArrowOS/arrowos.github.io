<?php
$VERSIONS = array(
    'arrow-11.0',
    'arrow-10.0',
    'arrow-9.x'
);

$VARIANTS = array(
    'official',
    'experiments',
    'community',
    'community_experiments'
);

$API_URL_CALLS = array(
    'oem_devices_list' => 'https://update.arrowos.net/api/v1/oem/devices/all/',
    'devices_version_list' => 'https://update.arrowos.net/api/v1/devices/version/all/',
    'devices_variant_list' => 'https://update.arrowos.net/api/v1/devices/variant/all/',
    'device_info' => 'https://update.arrowos.net/api/v1/info/{device}/{variant}/{version}/{zipvariant}',
    'source_changelog' => 'https://update.arrowos.net/api/v1/source/changelog'
);
?>