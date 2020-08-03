<?php
require_once('simplehtmldom/simple_html_dom.php');

function fetch_api_data($url)
{
    $result = [];
    $pattern = '{HTTP\/\S*\s(\d{3})}';
    $content = file_get_contents($url, false, stream_context_create(['http' => ['ignore_errors' => true]]));
    $response = $http_response_header[0];
    preg_match($pattern, $response, $response_code);
    $result['code'] = $response_code[1];
    $result['data'] = $content;

    return $result;
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
