<?php
include_once('utils.php');

if (isset($_GET['device'])) {
    $device = $_GET['device'];
    $device_info = fetch_api_data("https://update.arrowos.net/api/v1/info/{$device}/vanilla/");
    $gapps_device_info = fetch_api_data("https://update.arrowos.net/api/v1/info/{$device}/gapps/");

    if ($device_info['code'] == "200" && $gapps_device_info['code'] == "200") {
        $device_info = json_decode($device_info['data'], true);
        $gapps_device_info = json_decode($gapps_device_info['data'], true);

        if (isset($device_info) && isset($gapps_device_info)) {
            $device_info = $device_info['response'][0];
            $gapps_device_info = $gapps_device_info['response'][0];
        }
    } else {
        exit("Failed to fetch device info!");
    }
} else {
    exit();
}
?>
<div class="center hide-on-large-only">
    <img class="main_logo" src="img/logo.png">
    <br>
    <br>
</div>

<div style="padding-top: 50px;" class="container">
    <h4 class="primary-color" style="padding-bottom: 20px;"><?php echo ucwords(strtolower($device_info['model'])) ?></h4>
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
        <h4 class="primary-color" style="padding-bottom: 20px;">Downloads</h4>
        <div class="col s12 m6 l6">
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
        <div class="col s12 m6 l6">
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
    <div class="row" style="overflow-x:auto;">
        <h4 class="primary-color" style="padding-bottom: 20px;">Integrity check</h4>
        <strong>The sha256 of the following build types are:</strong>
        <table class="highlight responsive-table">
            <tbody>
                <tr>
                    <td><b>VANILLA:</b></td>
                    <td><?php echo $device_info['sha256'] ?></td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td><b>GAPPS:</b></td>
                    <td><?php echo $gapps_device_info['sha256'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <strong>You can check sha256sum via the following command:</strong><br>
    <b>Windows (Powershell):</b>
    <blockquote class="block">Get-filehash filename.zip</blockquote>
    <b>Linux (Ubuntu 20.4):</b>
    <blockquote class="block">sha256sum filename.zip</blockquote>
</div>

<div style="margin-bottom: 60px;margin-top: 60px; background-color: #424242;" class="divider"></div>

<div class="container">
    <div class="row">
        <h4 class="primary-color" style="padding-bottom: 20px;">Changelogs</h4>
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

<div class="container">
    <div class="row">
        <h4 class="primary-color" style="padding-bottom: 20px;">Ads</h4>
        <div class="col s12 m6 l10">
            <div class="card card-theme-color z-depth-3 radius">
                <div class="center card-content">
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle" style="display:block; text-align:center;" data-ad-layout="in-article" data-ad-format="fluid" data-ad-client="ca-pub-5568741006164863" data-ad-slot="7462336018"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>