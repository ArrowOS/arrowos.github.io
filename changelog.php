<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <meta name="description" content="ArrowOS source changelogs">
    <title>ArrowOS | Changelog</title>

    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
    <link href="css/index_debug.css" type="text/css" rel="stylesheet" media="screen,projection" />

    <!-- JS -->
    <script src="js/jquery-3.5.1.min.js"></script>
</head>

<body>

    <nav class="nav-background black-text z-depth-0">
        <div class="nav-wrapper container">
            <div class="nav-wrapper ">
                <ul class="left">
                    <li class="tab col s3">
                        <a title="Close" id="changelog-page-back" class="main-font white-text"> <i class="close material-icons">close</i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div style="padding-top: 10vh;" class="center">
                <img class="main_logo" src="img/logo.png">
                <br><br>
                <h4>Source Changelog</h4>
                <br><br><br>
                <div class="col s12 m10 l10 offset-l1 offset-m1">
                    <div class="card card-theme-color darken-1">
                        <div class="card-content white-text">
                            <ul style="border-width: 0px;" class="collapsible z-depth-0">
                                <?php
                                include_once("./config/constants.php");
                                require_once("utils.php");
                                $is_first = true;
                                $changelogs = fetch_api_data($API_URL_CALLS['source_changelog'])['data'];
                                $changelogs = json_decode($changelogs, true);
                                foreach ($changelogs as $version => $dates) {
                                ?>
                                    <li class="active">
                                        <div class="collapsible-header card-theme-color"><i class="tiny material-icons">label</i><?php echo ucfirst($version) ?></div>
                                        <div class="collapsible-body">
                                            <ul style="border-width: 0px;" class="collapsible z-depth-0">
                                                <?php
                                                $dates = array_keys($dates);
                                                usort($dates, "compareByTimeStamp");
                                                foreach ($dates as $date) {
                                                ?>
                                                    <li <?php if ($is_first) { ?> class="active" <?php }
                                                                                            $is_first = false ?>>
                                                        <div class="collapsible-header card-theme-color"><i class="tiny material-icons">calendar_today</i><?php echo $date ?></div>
                                                        <div class="collapsible-body">
                                                            <?php
                                                            foreach (explode(PHP_EOL, $changelogs[$version][$date]) as $log) {
                                                            ?>
                                                                <p class="text-align-left">
                                                                    <?php
                                                                    echo trim($log);
                                                                    ?>
                                                                </p>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="col s12 m8 l8 offset-l2 offset-m2">
                <div class="card card-theme-color z-depth-3 radius">
                    <div class="center card-content">
                        <span class="card-title">Oof Ads</span>
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
    </div>
    <script>
        $(document).ready(function() {
            $('.collapsible').collapsible();
            $('body').on('click', '#changelog-page-back', function() {
                window.location.href = "download.php";
            });
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>


</html>