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
            $vanilla_device_info = fetch_api_data(
                str_replace(
                    array('{device}', '{variant}', '{version}', '{zipvariant}'),
                    array($device, $deviceVariant, $deviceVersion, 'pie'),
                    $API_URL_CALLS['device_info']
                )
            );
            break;
        }

        $vanilla_device_info = fetch_api_data(
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

    if ($vanilla_device_info['code'] == "200" || $gapps_device_info['code'] == "200") {
        $vanilla_device_info = json_decode($vanilla_device_info['data'], true);
        $gapps_device_info = json_decode($gapps_device_info['data'], true);

        $vanilla_device_info = $vanilla_device_info['response'][0];
        $gapps_device_info = $gapps_device_info['response'][0];

        if (!isset($vanilla_device_info) && !isset($gapps_device_info)) http_response_code(404);

        if (isset($vanilla_device_info))
            $initial_device_info = $vanilla_device_info;
        elseif (isset($gapps_device_info))
            $initial_device_info = $gapps_device_info;
    } else {
        http_response_code(404);
    }
} else {
    exit("Something went wrong!");
}
?>

<div style="padding-top: 50px;" class="container">
    <div class="row">
        <div class="row">
            <h4 class="primary-color" style="padding-bottom: 20px;"><?php echo ucwords(strtolower($initial_device_info['model']));
                                                                    if (!$initial_device_info['status']) echo " [DISCONTINUED]"; ?></h4>
        </div>
        <div style="padding-left: 15px;" class="row">
            <div class="card card-theme-color darken-1 col s12 m12 l10 ">
                <div class="card-content white-text">
                    <h5 id="device-codename" name="<?php echo $device ?>">Codename:</h5> <?php echo ucfirst($device) ?>
                    <h5>Maintained by:</h5> <?php echo ucfirst($initial_device_info['maintainer']) ?>
                </div>

            </div>
        </div>
        <div style="padding-left: 15px;" class="row">
            <div class="col s12 m12 l10 ">
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-gw-3+1f-3d+2z" data-ad-client="ca-pub-5568741006164863" data-ad-slot="9060655737"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>
    </div>
</div>

<div style="margin-bottom: 25px;margin-top: 25px; background-color: #424242;" class="divider"></div>

<div class="container">
    <div class="row">
        <div class="row">
            <h4 id="downloads-section" class="primary-color" style="padding-bottom: 20px;">Downloads</h4>

            <div class="input-field col s12 m4 l4">
                <select id="version-selector" selected="selected">
                    <?php foreach ($VERSIONS as $version) { ?>
                        <option value="<?php echo strtolower($version); ?>" <?php if (!in_array(strtolower($version), $supportedVersions)) {
                                                                                echo "disabled";
                                                                            } ?>><?php echo ucfirst($version); ?></option>
                    <?php } ?>
                </select>
                <label>Select version</label>
            </div>

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
        <?php if (isset($vanilla_device_info)) { ?>
            <div class="col s12 m6 l6">
                <div class="card card-theme-color darken-1">
                    <div class="card-content white-text">
                        <span class="card-title"><b>VANILLA</b> build</span>
                        <p><b>Size:</b> <?php echo number_format((float)$vanilla_device_info['size'] / 1000000, 2, '.', '') ?> MB</p>
                        <p><b>Type:</b> <?php echo ucfirst($vanilla_device_info['type']) ?></p>
                        <p id="vanilla-version"><b>Version:</b> <?php echo $vanilla_device_info['version'] ?></p>
                        <p><b>Date:</b> <?php echo $vanilla_device_info['date'] ?></p>
                        <p id="vanilla-datetime" name="<?php echo $vanilla_device_info['datetime'] ?>"></p>
                        <p id="vanilla-filename" name="<?php echo $vanilla_device_info['filename'] ?>"></p>
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
        <?php } ?>
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
    <div class="center">
        <a id="downloads-archive" class="btn-small card card-theme-color waves-effect grey darken-4 waves-light">
            <i class="material-icons left">archive</i>archive
        </a>
        <div id="archive-fetch-progress" class="center"></div>
    </div>
    <br>
    <div class="center">
        <div id="datapacket" style="text-align: center">
            <span class="datapacket-text">Powered by</span>
            <a href="https://www.datapacket.com/" target="_blank"><img class="datapacket-logo" src="/img/datapacket_logo.png" alt="Datapacket"></a>
        </div>
    </div>
</div>

<div style="margin-bottom: 25px;margin-top: 25px; background-color: #424242;" class="divider"></div>

<div class="container">
    <div class="row" style="overflow-x:auto;">
        <h4 class="primary-color" style="padding-bottom: 20px;">Integrity check</h4>
        <strong>The sha256 of the following build types are:</strong>
        <table class="highlight responsive-table">
            <?php if (isset($vanilla_device_info)) { ?>
                <tbody>
                    <tr>
                        <td><b>VANILLA:</b></td>
                        <td id="vanilla-file_sha256"><?php echo $vanilla_device_info['sha256'] ?></td>
                    </tr>
                </tbody>
            <?php } ?>
            <?php if (isset($gapps_device_info)) { ?>
                <tbody>
                    <tr>
                        <td><b>GAPPS:</b></td>
                        <td id="gapps-file_sha256"><?php echo $gapps_device_info['sha256'] ?></td>
                    </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>
    <a href="https://blog.arrowos.net/posts/checking-build-integrity" target="_blank">Check our blog post for detailed info.</a>
    <div style="padding-left: 15px;" class="row">
        <div class="col s12 m12 l10 ">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-gw-3+1f-3d+2z" data-ad-client="ca-pub-5568741006164863" data-ad-slot="9060655737"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
</div>

<div style="margin-bottom: 25px;margin-top: 25px; background-color: #424242;" class="divider"></div>

<div class="container">
    <div class="row">
        <h4 class="primary-color" style="padding-bottom: 20px;">Changelogs</h4>
        <div class="col s12 m12 l10">
            <div class="card card-theme-color darken-1">
                <div class="card-content white-text">
                    <span class="card-title">Device side changes</span>
                    <p>
                        <?php
                        echo nl2br(stripcslashes($initial_device_info['changelog']));
                        ?>
                    </p>
                </div>
            </div>
            <a id="source-changelog" class="btn-large card card-theme-color waves-effect grey darken-4 waves-light" href="/changelog.php"><i class="material-icons left">reorder</i>source changelog</a>
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
<script src="/js/device.js"></script>
