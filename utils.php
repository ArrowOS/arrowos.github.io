<?php
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
