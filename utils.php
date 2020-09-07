<?php
require_once('simplehtmldom/simple_html_dom.php');

function fetch_api_data($url)
{
    $result = [];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

    $content = curl_exec($curl);
    $result['code'] = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $result['data'] = substr($content, $headerSize);
    curl_close($curl);

    return $result;
}

function get_device_versions($device_versions, $device_codename)
{
    $dev_versions = "";

    foreach (array_keys($device_versions) as $version) {
        foreach ($device_versions[$version] as $codename) {
            if ($codename == $device_codename) {
                $dev_versions =  $dev_versions . "," . $version;
            }
        }
    }

    return substr($dev_versions, 1);
}

if (isset($_POST['url'])) {
    $mirrorsList = array();
    $mirrors = file_get_html($_POST['url']);

    foreach ($mirrors->find('#mirrorList li') as $mirror) {
        foreach ($mirror->find('li') as $mirrorName) {
            $mirrorPlace = explode(',', explode('(', $mirrorName->plaintext)[1])[0];
            $mirrorsList[$mirrorPlace] = $mirrorName->id;
        }
    }
    echo json_encode($mirrorsList);

    $mirrors->clear();
    unset($mirrors);
}
