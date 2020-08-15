$(document).ready(function() {
    var forceFetch = 0;
    var filetype;
    $('body').on('click', '#fetch-mirrors', function() {
        filetype = $(this).attr('name');
        var deviceCodeName = $('#device-codename').attr('name');
        var datetime = $('#' + filetype + '-datetime').attr('name');
        var mirrorsUrl = 'https://sourceforge.net/settings/mirror_choices?projectname=';
        var projectName = 'arrow-os';
        var version = $('#' + filetype + '-version').text().split(" ")[1].slice(1);
        var filename = $('#' + filetype + '-filename').attr('name');
        var filepath = '/arrow-' + version + "/" + deviceCodeName + '/' + filename.trim();

        mirrorsUrl = mirrorsUrl + projectName + '&filename=' + filepath;

        if (localStorage.getItem(filetype + '_filedate_' + deviceCodeName) === filetype + '-' + datetime && forceFetch != 1) {
            if (localStorage.getItem(filetype + '_mirrors_' + deviceCodeName) != null) {
                $('#mirrors-content').load("mirror.html", function() {
                    $('#device-content').hide();
                    $('.navbar-fixed').show();
                    $('#filename-title').append(filename);
                    $('#display-mirrors').append(localStorage.getItem(filetype + '_mirrors_' + deviceCodeName));
                });
            }
        } else {
            $.ajax({
                type: 'POST',
                data: { 'url': mirrorsUrl },
                url: 'utils.php',
                beforeSend: function() {
                    $('#' + filetype + '-fetch-progress').append('<div class="progress"><div class="indeterminate"></div></div>');
                },
                success: function(data) {
                    var mirrorsData = $.parseJSON(data);
                    $('#' + filetype + '-fetch-progress').empty();
                    $('#mirrors-content').load("mirror.html", function() {
                        $('#device-content').hide();
                        $('.navbar-fixed').show();
                        $('#filename-title').append(filename);

                        // Always show the master mirror
                        $('#display-mirrors').append(
                            '<div class="chip">' +
                            '<i class="close material-icons">cloud</i>' +
                            '<a target="_blank" style="color: #141414;" href="https://master.dl.sourceforge.net/project/' + projectName + filepath + '">MASTER</a>' +
                            '</div>'
                        );

                        $.each(mirrorsData, function(mirrorPlace, mirrorName) {
                            $('#display-mirrors').append(
                                '<div class="chip">' +
                                '<i class="close material-icons">cloud</i>' +
                                '<a target="_blank" style="color: #141414;" href="https://' + mirrorName + '.dl.sourceforge.net/project/' + projectName + filepath + '">' + mirrorPlace + '</a>' +
                                '</div>'
                            );
                        });
                        localStorage.setItem(filetype + '_mirrors_' + deviceCodeName, $('#display-mirrors').html());
                        localStorage.setItem(filetype + '_filedate_' + deviceCodeName, filetype + '-' + datetime);
                        forceFetch = 0;
                    });
                }
            });
        }
    });

    $('body').on('click', '#device-page-back', function() {
        $('.navbar-fixed').hide();
        $('#device-content').show();
        $('#mirrors-content').empty();

        if ($('#downloads-section').html() != null) {
            $('html, body').animate({
                scrollTop: $("#downloads-section").offset().top
            }, 500);
        }
    });

    $('body').on('click', '#mirrors-refresh', function() {
        forceFetch = 1;
        $('#display-mirrors').empty();
        $('#display-mirrors').append('<div class="progress"><div class="indeterminate"></div></div>');
        $("#fetch-mirrors[name='" + filetype + "']").trigger('click');
    });
});