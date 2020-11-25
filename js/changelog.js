$(document).ready(function () {
    $('.collapsible').collapsible();
    var changelogData = '';
    var version = '';

    $('body').on('click', '#changelog-page-back', function () {
        window.location.href = "/download";
    });

    $('.collapsible').collapsible({
        onOpenStart: function (ele) {
            version = $(ele).find('#changelog-version').data('changelog_version');
            $.ajax({
                type: 'POST',
                data: {
                    'gerrit_changelog': 'yes',
                    'version': version
                },
                beforeSend: function () {
                    version = version.split('.')[0];
                    $('#changelog-progress-' + version).append('<div class="progress"><div class="indeterminate"></div></div>');
                },
                url: '/utils.php',
                success: function (data) {
                    changelogData = data;
                },
                complete: function (xhr) {
                    if (xhr.status === 200) {
                        $('#changelog-data-' + version).empty();
                        changelogData = $.parseJSON(changelogData);
                        console.log(changelogData);
                        $.each(changelogData, function (date, changeNum) {
                            $('#changelog-data-' + version).append('<h4><u>Changelog on ' + date + '</u></h4><br>')
                            $.each(changeNum, function (changeNum, project) {
                                $.each(project, function (project, changeSubject) {
                                    $('#changelog-data-' + version).append(
                                        '<p class="text-align-left"><b>' + project + '</b>' +
                                        ': <a href="https://review.arrowos.net/#/c/' + changeNum +
                                        '" target="_blank">' + changeSubject + '</a></p?'
                                    )
                                })
                            })
                            $('#changelog-data-' + version).append(
                                '<div style="padding-left: 0px;" class="row">' +
                                '<div class="col s12 m12 l12 ">' +
                                '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>' +
                                '<ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-gw-3+1f-3d+2z" data-ad-client="ca-pub-5568741006164863" data-ad-slot="9060655737"></ins>' +
                                '<script>' +
                                '(adsbygoogle = window.adsbygoogle || []).push({});' +
                                '</script>' +
                                '</div>' +
                                '</div>'
                            )
                        })
                    }
                    $('#changelog-progress-' + version).empty();
                }
            });
        }
    })
});

$(window).on('load', function () {
    $('.collapsible-header').first().trigger('click')
})