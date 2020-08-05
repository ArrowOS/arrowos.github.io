$(document).ready(function() {
    var deviceCodeName = localStorage.device;
    var open_checks = {};
    open_checks[deviceCodeName + '_vanilla'] = 0;
    open_checks[deviceCodeName + '_gapps'] = 0;

    $('body').on('click', '#fetch-mirrors', function() {
        var filetype = $(this).attr('name');
        var datetime = $('#' + filetype + '-datetime').attr('name');

        /* Wrote this dirty hack late night, not sure anymore how or what it does but do not remove it */
        if (deviceCodeName != $('#device-codename').attr('name')) {
            deviceCodeName = $('#device-codename').attr('name');
            open_checks[deviceCodeName + '_vanilla'] = 0;
            open_checks[deviceCodeName + '_gapps'] = 0;
        }

        if (open_checks[deviceCodeName + '_' + filetype] === 0) {
            if (localStorage.getItem(filetype + '_filedate_' + deviceCodeName) === filetype + '-' + datetime) {
                if (localStorage.getItem(filetype + '_mirrors_' + deviceCodeName) != null) {
                    $('#' + filetype + '-mirrors').append(localStorage.getItem(filetype + '_mirrors_' + deviceCodeName));
                    open_checks[deviceCodeName + '_' + filetype] = 1;
                }
            } else {
                var mirrorsUrl = 'https://sourceforge.net/settings/mirror_choices?projectname=';
                var projectName = 'arrow-os';
                var version = $('#' + filetype + '-version').text().split(" ")[1].slice(1);
                var filename = $('#' + filetype + '-filename').attr('name');
                var filepath = '/arrow-' + version + "/" + deviceCodeName + '/' + filename.trim();

                mirrorsUrl = mirrorsUrl + projectName + '&filename=' + filepath;
                $.ajax({
                    type: 'POST',
                    data: { 'url': mirrorsUrl },
                    url: 'utils.php',
                    beforeSend: function() {
                        $('#' + filetype + '-mirrors').append('<div class="progress"><div class="indeterminate"></div></div>');
                    },
                    success: function(data) {
                        var mirrorsData = $.parseJSON(data);
                        $('#' + filetype + '-mirrors').empty();
                        $.each(mirrorsData, function(mirrorPlace, mirrorName) {
                            $('#' + filetype + '-mirrors').append('<a class="waves-effect waves-light btn-small" href="https://' + mirrorName + '.dl.sourceforge.net/project' + filepath + '">' + mirrorPlace + '</a>');
                        });
                        localStorage.setItem(filetype + '_mirrors_' + deviceCodeName, $('#' + filetype + '-mirrors').html());
                        localStorage.setItem(filetype + '_filedate_' + deviceCodeName, filetype + '-' + datetime);
                        open_checks[deviceCodeName + '_' + filetype] = 1;
                    }
                });
            }
        }
    });
});