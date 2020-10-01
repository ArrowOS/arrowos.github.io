$(document).ready(function () {
    var forceFetch = 0;
    var filetype;
    $('body').on('click', '#fetch-mirrors:not(.clicked)', function () {
        $('#fetch-mirrors').addClass('clicked')
        filetype = $(this).attr('name');
        var deviceCodeName = $('#device-codename').attr('name');
        var datetime = $('#' + filetype + '-datetime').attr('name');
        var mirrorsUrl = 'https://sourceforge.net/settings/mirror_choices?projectname=';
        var version = 'arrow-' + $('#' + filetype + '-version').text().split(" ")[1].slice(1);
        // Hardcode for legacy fallback version
        version = version.includes('9') ? 'arrow-9.x' : version;
        var filename = $('#' + filetype + '-filename').attr('name');
        var filepath = '/' + version + "/" + deviceCodeName + '/' + filename.trim();
        var file_sha256 = $('#' + filetype + '-file_sha256').text();

        var arrowMirror = '';
        var arrowMirrorResp = '';
        var mirrorsData = '';

        var variant = localStorage.getItem(deviceCodeName + '_' + version + '_variant');
        if (variant == 'official') {
            projectName = 'arrow-os';
        } else if (variant == 'experiments') {
            projectName = 'arrowos-beta';
        } else if (variant == 'community') {
            projectName = 'arrowos-community';
        } else if (variant == 'community_experiments') {
            projectName = 'arrowos-beta'
            filepath = '/arrow-community' + filepath;
        }
        mirrorsUrl = mirrorsUrl + projectName + '&filename=' + filepath;

        // Fetch arrow mirror
        $.ajax({
            type: 'POST',
            data: {
                'file_sha256': file_sha256,
                'version': version,
                'variant': variant,
                'filename': filename
            },
            beforeSend: function () {
                $('#' + filetype + '-fetch-progress').append('<div class="progress"><div class="indeterminate"></div></div>');
            },
            url: 'https://get.mirror1.arrowos.net/download.php',
            success: function (data) {
                arrowMirror = data;
            },
            complete: function (xhr) {
                arrowMirrorResp = xhr.status;
                // SF mirrors
                if (localStorage.getItem(filetype + version + variant + '_filedate_' + deviceCodeName) === filetype + '-' + datetime && forceFetch != 1) {
                    if (localStorage.getItem(filetype + version + variant + '_mirrors_' + deviceCodeName) != null) {
                        setSavedMirrorData();
                    }
                } else {
                    fetchSFMirrorData();
                }
            }
        });

        function setSavedMirrorData() {
            var pageContent = '';
            $.ajax({
                url: "/mirror.html",
                cache: false,
                dataType: "html",
                success: function (data) {
                    pageContent = data;
                },
                complete: function () {
                    $('#' + filetype + '-fetch-progress').empty();
                    $('#mirrors-content').html(pageContent);
                    $('#device-content').hide();
                    $('.navbar-fixed').show();
                    $('#filename-title').append(filename);
                    setArrowMirror();
                    $('#display-mirrors').append(localStorage.getItem(filetype + version + variant + '_mirrors_' + deviceCodeName));

                    $('html, body').animate({
                        scrollTop: $("#mirrors-section").offset().top - $(window).height() / 2
                    }, 1000);
                }
            });
            $('#fetch-mirrors').removeClass('clicked')
        }

        function fetchSFMirrorData() {
            // Fetch SF mirrors
            $.ajax({
                type: 'POST',
                data: { 'url': mirrorsUrl },
                url: '/utils.php',
                beforeSend: function () {
                    $('#' + filetype + '-fetch-progress').empty();
                    $('#' + filetype + '-fetch-progress').append('<div class="progress"><div class="indeterminate"></div></div>');
                },
                success: function (data) {
                    mirrorsData = data;
                },
                complete: function () {
                    showMirrorsContent(mirrorsData, arrowMirror);
                }
            });
        }

        function showMirrorsContent() {
            mirrorsData = $.parseJSON(mirrorsData);
            $('#' + filetype + '-fetch-progress').empty();
            $.ajax({
                url: "/mirror.html",
                cache: false,
                dataType: "html",
                success: function (data) {
                    $('#mirrors-content').html(data);
                    $('#device-content').hide();
                    $('.navbar-fixed').show();
                    $('#filename-title').append(filename);

                    setArrowMirror();
                    $.each(mirrorsData, function (mirrorPlace, mirrorName) {
                        $('#display-mirrors').append(
                            '<div class="chip">' +
                            '<a target="_blank" style="color: #141414;" href="https://' + mirrorName + '.dl.sourceforge.net/project/' + projectName + filepath + '">' +
                            '<i class="close material-icons">cloud</i>' + mirrorPlace + '</a>' +
                            '</div>'
                        );
                    });
                    localStorage.setItem(filetype + version + variant + '_mirrors_' + deviceCodeName, $('#display-mirrors').html());
                    localStorage.setItem(filetype + version + variant + '_filedate_' + deviceCodeName, filetype + '-' + datetime);
                    forceFetch = 0;

                    $('html, body').animate({
                        scrollTop: $("#mirrors-section").offset().top - $(window).height() / 2
                    }, 1000);
                }
            });
            $('#fetch-mirrors').removeClass('clicked')
        }

        function setArrowMirror() {
            if (arrowMirror != null && arrowMirror != '' && arrowMirrorResp === 200) {
                $('#arrow-mirrors').append(
                    '<div class="chip">' +
                    '<a target="_blank" style="color: #141414;" href="' + arrowMirror + '">' +
                    '<i class="close material-icons">cloud</i>arrow1</a>' +
                    '</div>' +
                    '<hr class="solid" style="border-top: 3px solid #bbb;">'
                );
            } else {
                $('#arrow-mirrors').append(
                    '<div class="chip">' +
                    '<a target="_blank" style="color: #141414;">File not found/removed!</a>' +
                    '</div>' +
                    '<hr class="solid" style="border-top: 3px solid #bbb;">'
                );
            }
        }
    });

    $('body').on('click', '#device-page-back', function () {
        $('.navbar-fixed').hide();
        $('#device-content').show();
        $('#mirrors-content').empty();

        if ($('#downloads-section').html() != null) {
            $('html, body').animate({
                scrollTop: $("#downloads-section").offset().top
            }, 500);
        }
    });

    $('body').on('click', '#mirrors-refresh', function () {
        forceFetch = 1;
        $('#display-mirrors').empty();
        $('#display-mirrors').append('<div class="progress"><div class="indeterminate"></div></div>');
        $("#fetch-mirrors[name='" + filetype + "']").trigger('click');
    });
});