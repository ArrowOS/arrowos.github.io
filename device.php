<?php
error_reporting(E_ALL & ~E_NOTICE);

include_once('./config/constants.php');
include_once('utils.php');

if (
    isset($_POST['device']) &&
    isset($_POST['deviceVariant']) &&
    isset($_POST['deviceVersion']) &&
    isset($_POST['supportedVersions']) &&
    isset($_POST['supportedVariants'])
) {

    $device = $_POST['device'];
    $deviceVariant = $_POST['deviceVariant'];
    $deviceVersion = $_POST['deviceVersion'];
    $supportedVersions = json_decode($_POST['supportedVersions']);
    $supportedVariants = json_decode($_POST['supportedVariants']);

    do {
        // Fallback legacy api call for arrow-9.x
        if ($deviceVersion == "arrow-9.x") {
            $device_info = fetch_api_data(
                str_replace(
                    array('{device}', '{variant}', '{version}', '{zipvariant}'),
                    array($device, $deviceVariant, $deviceVersion, 'pie'),
                    $API_URL_CALLS['device_info']
                )
            );
            break;
        }

        $device_info = fetch_api_data(
            str_replace(
                array('{device}', '{variant}', '{version}', '{zipvariant}'),
                array($device, $deviceVariant, $deviceVersion, 'vanilla'),
                $API_URL_CALLS['device_info']
            )
        );
        $gapps_device_info = fetch_api_data(
            str_replace(
                array('{device}', '{variant}', '{version}', '{zipvariant}'),
                array($device, $deviceVariant, $deviceVersion, 'gapps'),
                $API_URL_CALLS['device_info']
            )
        );
        break;
    } while (0);

    if ($device_info['code'] == "200" || $gapps_device_info['code'] == "200") {
        $device_info = json_decode($device_info['data'], true);
        $gapps_device_info = json_decode($gapps_device_info['data'], true);

        $device_info = $device_info['response'][0];
        $gapps_device_info = $gapps_device_info['response'][0];
    } else {
        http_response_code(404);
    }
} else {
    exit("Something went wrong!");
}
?>
<div class="center hide-on-large-only">
    <img class="main_logo" src="img/logo.png">
    <br>
    <br>
</div>

<div style="padding-top: 50px;" class="container">
    <div class="row">
        <div class="row">
            <h4 class="primary-color" style="padding-bottom: 20px;"><?php echo ucwords(strtolower($device_info['model']));
                                                                    if (!$device_info['status']) echo " [DISCONTINUED]"; ?></h4>

            <div class="input-field col s12 m4 l4">
                <select id="version-selector" selected="selected">
                    <?php foreach ($VERSIONS as $version) { ?>
                        <option value="<?php echo strtolower($version); ?>" <?php if (!in_array(strtolower($version), $supportedVersions)) {
                                                                                echo "disabled";
                                                                            } ?>><?php echo ucfirst($version); ?></option>
                    <?php } ?>
                </select>
                <label">Select version</label>
            </div>
        </div>
        <div style="padding-left: 15px;" class="row">
            <div class="card card-theme-color darken-1 col s12 m12 l10 ">
                <div class="card-content white-text">
                    <h5 id="device-codename" name="<?php echo $device ?>">Codename:</h5> <?php echo ucfirst($device) ?>
                    <h5>Maintained by:</h5> <?php echo ucfirst($device_info['maintainer']) ?>
                </div>

            </div>
        </div>
    </div>
</div>

<div style="margin-bottom: 60px;margin-top: 60px; background-color: #424242;" class="divider"></div>

<div class="container">
    <div class="row">
        <div class="row">
            <h4 id="downloads-section" class="primary-color" style="padding-bottom: 20px;">Downloads</h4>

            <div class="input-field col s12 m4 l4">
                <select id="variant-selector" selected="selected">
                    <?php foreach ($VARIANTS as $variant) { ?>
                        <option value="<?php echo strtolower($variant) ?>" <?php if ($variant == "community_experiments" && in_array("community", $supportedVariants)) echo "";
                                                                            elseif (!in_array(strtolower($variant), $supportedVariants)) {
                                                                                echo "disabled";
                                                                            } ?>><?php echo ($variant == "community_experiments") ? ucfirst(str_replace('_', ' ', $variant)) : ucfirst($variant) ?></option>
                    <?php } ?>
                </select>
                <label>Select Build</label>
            </div>
        </div>
        <div class="col s12 m6 l6">
            <div class="card card-theme-color darken-1">
                <div class="card-content white-text">
                    <span class="card-title"><b>VANILLA</b> build</span>
                    <p><b>Size:</b> <?php echo number_format((float)$device_info['size'] / 1000000, 2, '.', '') ?> MB</p>
                    <p><b>Type:</b> <?php echo ucfirst($device_info['type']) ?></p>
                    <p id="vanilla-version"><b>Version:</b> <?php echo $device_info['version'] ?></p>
                    <p><b>Date:</b> <?php echo $device_info['date'] ?></p>
                    <p id="vanilla-datetime" name="<?php echo $device_info['datetime'] ?>"></p>
                    <p id="vanilla-filename" name="<?php echo $device_info['filename'] ?>"></p>
                </div>
                <div style="border-radius: 10px;" class="card-action center">
                    <ul>
                        <li>
                            <a class="btn-flat">
                                <div id="fetch-mirrors" name="vanilla" class="card-theme-color center">
                                    <i class="material-icons">file_download</i>
                                    DOWNLOAD
                                </div>
                            </a>
                        </li>
                        <li>
                            <div id="vanilla-fetch-progress" class="center"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php if (isset($gapps_device_info)) { ?>
            <div class="col s12 m6 l6">
                <div class="card card-theme-color">
                    <div class="card-content white-text">
                        <span class="card-title"><b>GAPPS</b> build</span>
                        <p><b>Size:</b> <?php echo number_format((float)$gapps_device_info['size'] / 1000000, 2, '.', '') ?> MB</p>
                        <p><b>Type:</b> <?php echo ucfirst($gapps_device_info['type']) ?></p>
                        <p id="gapps-version"><b>Version:</b> <?php echo $gapps_device_info['version'] ?></p>
                        <p><b>Date:</b> <?php echo $gapps_device_info['date'] ?></p>
                        <p id="gapps-datetime" name="<?php echo $gapps_device_info['datetime'] ?>"></p>
                        <p id="gapps-filename" name="<?php echo $gapps_device_info['filename'] ?>"></p>
                    </div>
                    <div style="border-radius: 10px;" class="card-action center">
                        <ul>
                            <li>
                                <a class="btn-flat">
                                    <div id="fetch-mirrors" name="gapps" class="card-theme-color center">
                                        <i class="material-icons">file_download</i>
                                        DOWNLOAD
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div id="gapps-fetch-progress" class="center"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>
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
            <?php if (isset($gapps_device_info)) { ?>
                <tbody>
                    <tr>
                        <td><b>GAPPS:</b></td>
                        <td><?php echo $gapps_device_info['sha256'] ?></td>
                    </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>
    <strong>You can check sha256sum via the following command examples:</strong><br>
    <b>Windows (Powershell):</b>
    <blockquote class="block">Get-filehash <?php echo $gapps_device_info['filename'] ?></blockquote>
    <b>Linux (Ubuntu 20.4):</b>
    <blockquote class="block">sha256sum <?php echo $device_info['filename'] ?></blockquote>
    <br>
    <a href="https://blog.arrowos.net/posts/checking-build-integrity">You can also check our blog post for the same.</a>
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
            <a id="source-changelog" class="btn-small card card-theme-color" href="changelog.php"><i class="material-icons left">reorder</i>source changelog</a>
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
<script src="js/device.js"></script>