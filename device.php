<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />

    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
    <link href="css/index_debug.css" type="text/css" rel="stylesheet" media="screen,projection" />

    <link href="css/download.css" type="text/css" rel="stylesheet">

    <!-- JS -->
    <script src="js/jquery-3.5.1.min.js"></script>
</head>

<body>
    <?php
    if (isset($_GET['device']))
        $device = $_GET['device'];
    $device_info = json_decode(file_get_contents("https://update.arrowos.net/api/v1/info/{$device}/vanilla/"), true);
    $gapps_device_info = json_decode(file_get_contents("https://update.arrowos.net/api/v1/info/{$device}/gapps/"), true);

    $device_info = $device_info['response'][0];
    $gapps_device_info = $gapps_device_info['response'][0];
    ?>
    <div class="center hide-on-large-only">
        <img class="main_logo" src="img/logo.png">
        <br>
        <br>
    </div>

    <div style="padding-top: 50px;" class="container">
        <h4 style="padding-bottom: 20px;"><?php echo ucwords(strtolower($device_info['model'])) ?></h4>
        <div style="padding-left: 15px;" class="row">
            <div class="card card-theme-color darken-1 col s12 m12 l10 ">
                <div class="card-content white-text">
                    <h5>Codename:</h5> <?php echo ucfirst($device) ?>
                    <h5>Maintained by:</h5> <?php echo ucfirst($device_info['maintainer']) ?>
                </div>

            </div>
        </div>
    </div>

    <div style="margin-bottom: 60px;margin-top: 60px; background-color: #424242;" class="divider"></div>

    <div class="container">
        <div class="row">
            <h4 style="padding-bottom: 20px;">Downloads</h4>
            <div class="col s12 m6 l5">
                <div class="card card-theme-color darken-1">
                    <div class="card-content white-text">
                        <span class="card-title"><b>VANILLA</b> build</span>
                        <p><b>Size:</b> <?php echo number_format((float)$device_info['size'] / 1000000, 2, '.', '') ?> MB</p>
                        <p><b>Type:</b> <?php echo ucfirst($device_info['type']) ?></p>
                        <p><b>Version:</b> <?php echo $device_info['version'] ?></p>
                        <p><b>Date:</b> <?php echo $device_info['date'] ?></p>
                    </div>
                    <div style="border-radius: 8px;" class="card-action">
                        <a class="primary-text" href="<?php echo $device_info['downloadurl'] ?>"><code><?php echo $device_info['filename'] ?></code></a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l5">
                <div class="card card-theme-color">
                    <div class="card-content white-text">
                        <span class="card-title"><b>GAPPS</b> build</span>
                        <p><b>Size:</b> <?php echo number_format((float)$gapps_device_info['size'] / 1000000, 2, '.', '') ?> MB</p>
                        <p><b>Type:</b> <?php echo ucfirst($gapps_device_info['type']) ?></p>
                        <p><b>Version:</b> <?php echo $gapps_device_info['version'] ?></p>
                        <p><b>Date:</b> <?php echo $gapps_device_info['date'] ?></p>
                    </div>
                    <div style="border-radius: 8px;" class="card-action">
                        <a class="primary-text" href="<?php echo $gapps_device_info['downloadurl'] ?>"><code><?php echo $gapps_device_info['filename'] ?></code></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 60px;margin-top: 60px; background-color: #424242;" class="divider"></div>

    <div class="container">
        <div class="row">
            <h4 style="padding-bottom: 20px;">Integrity check</h4>
                        <table class="highlight responsive-table">
                            <blockquote>You can check the sha256 of the following build types</blockquote>
                                <tr>
                                    <td><b>VANILLA:</b></td>
                                    <td><?php echo $device_info['sha256'] ?></code></td>
                                </tr>
                                <tr>
                                    <td><b>GAPPS:</b></td>
                                    <td><?php echo $gapps_device_info['sha256'] ?></td>
                                </tr>
                        </table>
        </div>
    </div>

    <div style="margin-bottom: 60px;margin-top: 60px; background-color: #424242;" class="divider"></div>

    <div class="container">
        <div class="row">
            <h4 style="padding-bottom: 20px;">Changelogs</h4>
            <div class="col s12 m12 l10">
                <div class="card card-theme-color darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">Device side changes</span>
                        <p>
                            <?php
                            echo nl2br(stripcslashes($device_info['changelog']));
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>