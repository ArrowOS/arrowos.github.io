<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>ArrowOS - Downloads</title>

    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
    <link href="css/index_debug.css" type="text/css" rel="stylesheet" media="screen,projection" />

    <link href="css/download.css" type="text/css" rel="stylesheet">
</head>

<body>
    <nav class="nav-background hide-on-large-only z-depth-0" role="navigation">
        <div class="nav-wrapper container">
            <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <ul id="slide-out" class="sidenav sidenav-fixed collapsible">
        <ul class="collapsible collapsible-accordion">
            <?php

            $devices_json = json_decode(file_get_contents("https://update.arrowos.net/api/v1/oem/devices/vanilla/"), true);
            ?>

            <?php
            foreach ($devices_json as $device_oem => $devices) {
            ?>
                <li class="bold"><a class="collapsible-header waves-effect "><?php echo ($device_oem != null) ? ($device_oem) : ("") ?></a>
                    <div class="collapsible-body">
                        <ul>
                            <?php
                            foreach ($devices as $device_codename) {
                            ?>
                                <li>
                                    <a id="deviceLabel"><?php echo $device_codename ?></a>
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
    </main>


    <footer class="page-footer card-theme-color">
        <div class="container">
        </div>
        <div class="footer-copyright">
            <div class="container footer-center">
                Designed by <b><a class="white-text" href="https://t.me/harshv23/">HarshV23
                    </a></b><br>Copyright © 2020 ArrowOS<br>
            </div>
        </div>
    </footer>


    <!--  Scripts-->
    <!-- JS -->
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/download.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>

</body>

</html>