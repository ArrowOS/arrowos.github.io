$(document).ready(function() {
    $('.navbar-fixed').hide();
    $('.collapsible').collapsible();
    $('.sidenav').sidenav();

    var prevSelectedDevice = localStorage.device;
    if (prevSelectedDevice == null) {
        $('#device-content').load("empty.html");
    } else {
        $('#device-content').addClass("scale-transition scale-out");
        $('#device-content').load("device.php", 'device=' + prevSelectedDevice, function() {
            $('select').formSelect();
            $('#device-content').removeClass("scale-transition scale-out");
            $('#device-content').addClass("scale-transition");
        });
    }

    $('body').on('click', '#deviceLabel', function() {
        $('#device-page-back').trigger('click');
        $('#device-content').addClass("scale-transition scale-out");
        selectedDevice = $(this).text();
        localStorage.setItem("device", selectedDevice);
        $('#device-content').load("device.php", 'device=' + selectedDevice, function() {
            $('select').formSelect();
            $('#device-content').removeClass("scale-transition scale-out");
            $('#device-content').addClass("scale-transition");
            $(window).scrollTop(0);
        });
    });

    $('body').on('click', '#select-device', function() {
        $('.sidenav').sidenav('open');
    });
});