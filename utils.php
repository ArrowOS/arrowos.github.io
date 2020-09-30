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

function get_device_data($device_data, $device_codename)
{
    $dev_data = array();
    $variants = array();

    foreach (array_keys($device_data[$device_codename]) as $version) {
        $dev_data[$version] = $version;
        foreach ($device_data[$device_codename][$version] as $variant) {
            array_push($variants, $variant);
        }
        $dev_data[$version] = array('variants' => $variants);
        $variants = array();
    }

    return json_encode($dev_data);
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

function compareByTimeStamp($time1, $time2)
{
    if (strtotime($time1) < strtotime($time2))
        return 1;
    else if (strtotime($time1) > strtotime($time2))
        return -1;
    else
        return 0;
}
