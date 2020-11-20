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

function fetch_gerrit_changes($branch) {
    $changeLog = array();
    $gerritDomain = "https://review.arrowos.net";
    $gerritUrl = $gerritDomain . "/changes/?q=status:merged+branch:" . $branch;
    $changes = file_get_contents($gerritUrl);
    $changes = json_decode(preg_replace('/^.+\n/', '', $changes));

    foreach($changes as $change) {
        $changeDate = explode(" ", $change->submitted)[0];
        $projectName = explode("/", $change->project)[1];
        $changeNum = $change->_number;
        $changeSubject = $change->subject;

        $changeLog[$changeDate][$projectName] = array();
        $changeLog[$changeDate][$projectName][$changeNum] = array();
        $changeLog[$changeDate][$projectName][$changeNum] = $changeSubject;
    }

    return $changeLog;
}

if (isset($_POST['gerrit_changelog']) && $_POST['gerrit_changelog'] == 'yes'
        && isset($_POST['version']))
    exit(json_encode(fetch_gerrit_changes($_POST['version'])));
