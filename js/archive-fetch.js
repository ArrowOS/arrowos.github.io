$(document).ready(function () {

    $('body').on('click', '#downloads-archive:not(.clicked)', function () {
        $('#downloads-archive').addClass('clicked');
        var deviceCodeName = $('#device-codename').attr('name');

        let archiveServerUrl = "https://get.mirror3.arrowos.net/archive/archive.php";

        let version = $('.select-dropdown').val() == null ? 'Arrow-12.1'  : $('.select-dropdown').val();

        if (version == "Arrow-12.1") {
                archiveServerUrl = 'https://get.mirror2.arrowos.net/archive/archive.php'
        }
        // Fetch archive link
	$.ajax({
            type: 'POST',
            data: { 'device': deviceCodeName },
            url: archiveServerUrl,
            beforeSend: function () {
                $('#archive-fetch-progress').empty();
                $('#archive-fetch-progress').append('<div class="progress"><div class="indeterminate"></div></div>');
            },
            success: function (data) {
                archiveUrl = data;
            },
            complete: function (xhr) {
                if (xhr.status === 200)
                    window.location.href = archiveUrl;
                else
                    alert("Failed to fetch archive url!");

                $('#archive-fetch-progress').empty();
                $('#downloads-archive').removeClass('clicked');
            }
        });
    });
});
