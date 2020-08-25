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
    <div class="container">
        <div class="row">
            <div style="padding-top: 10vh;" class="center">
                <img class="main_logo" src="img/logo.png">
                <br><br>
                <h4>Source Changelogs</h4>
                <br>
                <br><br>
                <div class="col s12 m6 l6 offset-l3 offset-m3">
                    <div class="card card-theme-color darken-1">
                        <div class="card-content white-text">
                            <ul style="border-width: 0px;" class="collapsible z-depth-0">
                                <?php
                                require_once("utils.php");
                                $is_first = true;
                                $changelogs = fetch_api_data("https://update.arrowos.net/api/v1/source/changelog")['data'];
                                $changelogs = json_decode($changelogs, true);
                                foreach ($changelogs as $version => $dates) {
                                ?>
                                    <li class="active">
                                        <div class="collapsible-header card-theme-color"><i class="tiny material-icons">label</i><?php echo ucfirst($version) ?></div>
                                        <div class="collapsible-body">
                                            <ul style="border-width: 0px;" class="collapsible z-depth-0">
                                                <?php
                                                foreach ($dates as $date => $log) {
                                                ?>
                                                    <li <?php if ($is_first) { ?> class="active" <?php }
                                                                                            $is_first = false ?>>
                                                        <div class="collapsible-header card-theme-color"><i class="tiny material-icons">calendar_today</i><?php echo $date ?></div>
                                                        <div class="collapsible-body">
                                                            <?php
                                                            foreach (explode(PHP_EOL, $log) as $log) {
                                                            ?>
                                                                <p>
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
                <br>
                <div class="col s12 m6 l6 offset-l3 offset-m3">
                    <div class="center">
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
    <script>
        $(document).ready(function() {
            $('.collapsible').collapsible();
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>


</html>