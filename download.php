<?php
include_once('./config/constants.php');
include_once('utils.php');
?>
<!DOCTYPE html>
<html lang="en" translate="no">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>ArrowOS - Downloads</title>

    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.4.55/css/materialdesignicons.min.css">
    <link href="/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
    <link href="/css/index_debug.css" type="text/css" rel="stylesheet" media="screen,projection" />

    <link href="/css/download.css" type="text/css" rel="stylesheet">
</head>

<body style="visibility: hidden !important;">
    <div id="babasbmsgx" style="visibility: visible !important;"></div>
    <?php if (isset($_GET['device'])) { ?>
        <div id="get_device" data-device="<?php echo $_GET['device'] ?>"></div>
    <?php } ?>
    <nav class="nav-background hide-on-large-only z-depth-0" role="navigation">
        <div class="nav-wrapper container">
            <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <div class="navbar-fixed">
        <nav class="nav-background black-text z-depth-0">
            <div class="nav-wrapper container">
                <div class="nav-wrapper ">
                    <ul class="right">
                        <li class="tab col s3">
                            <a title="Refresh mirrors" id="mirrors-refresh" class="main-font white-text"> <i class="close material-icons">refresh</i></a>
                        </li>
                        <li class="tab col s3">
                            <a title="Close" id="device-page-back" class="main-font white-text"> <i class="close material-icons">close</i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <ul id="slide-out" class="sidenav sidenav-fixed collapsible grey lighten-2">
        <ul class="collapsible collapsible-accordion">
            <?php
            $devices_json = fetch_api_data($API_URL_CALLS['oem_devices_list']);
            $devices_support_info = fetch_api_data($API_URL_CALLS['devices_support_info']);

            if ($devices_json['code'] == "200" && $devices_support_info['code'] == "200") {
                $devices_json = json_decode($devices_json['data'], true);
                $devices_support_info = json_decode($devices_support_info['data'], true);

                ksort($devices_json, SORT_STRING | SORT_FLAG_CASE);
            } else {
                exit("Failed to fetch devices!");
            }
            ?>

            <?php
            foreach ($devices_json as $device_oem => $devices) {
            ?>
                <li class="bold"><a class="collapsible-header waves-effect " style="font-weight:bold"><?php echo ($device_oem != null) ? (ucfirst($device_oem)) : ("") ?></a>
                    <div class="collapsible-body">
                        <ul>
                            <?php
                            asort($devices);
                            foreach ($devices as $device_codename) {
                            ?>
                                <li>
                                    <?php $supported_info = get_device_data($devices_support_info, $device_codename) ?>
                                    <a href="javascript:void(0);" class="sidenav-close" id="deviceLabel" data-supported='<?php print_r($supported_info) ?>'><?php echo $device_codename ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </ul>

    <main>
        <div id="device-content"></div>
        <div id="mirrors-content"></div>
    </main>
    <br>
    <footer class="page-footer card-theme-color">
        <div class="container">
            <div class="center">
                <a class="btn-floating btn-small waves-effect waves-light white" href="https://t.me/arrowos"><i class="mdi mdi-telegram grey-text text-darken-3"></i></a>
                <a class="btn-floating btn-small waves-effect waves-light white footer-button" href="https://github.com/arrowos"><i class="mdi mdi-github grey-text text-darken-3"></i></a>
                <a class="btn-floating btn-small waves-effect waves-light white footer-button" href="https://review.arrowos.net"><i class="mdi mdi-git grey-text text-darken-3"></i></a>
                <a class="btn-floating btn-small waves-effect waves-light white footer-button" href="https://crowdin.com/project/arrowos"><i class="mdi mdi-translate grey-text text-darken-3"></i></a>
                <a class="btn-floating btn-small waves-effect waves-light white footer-button" href="https://stats.arrowos.net"><i class="mdi mdi-chart-box-outline grey-text text-darken-3"></i></a>
                <a class="btn-floating btn-small waves-effect waves-light white footer-button" href="https://blog.arrowos.net"><i class="mdi mdi-blogger grey-text text-darken-3"></i></a>
            </div>
            <br>
        </div>
        <div class="footer-copyright">
            <div class="container footer-center">
                Designed by <b><a style="font-size: medium;" class="white-text" href="https://t.me/harshv23/">HarshV23
                    </a></b><br>Copyright © 2022 ArrowOS<br><br>
            </div>
        </div>
    </footer>


    <!--  Scripts-->
    <!-- JS -->
    <script src="/js/blockAdBlock.js"></script>
    <script src="/js/jquery-3.5.1.min.js"></script>
    <script src="/js/materialize.js"></script>
    <script src="/js/download.js"></script>
    <script src="/js/archive-fetch.js"></script>
    <script src="/js/sf-mirror-fetch.js"></script>

</body>

</html>