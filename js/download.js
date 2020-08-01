$(document).ready(function() {
    $('.collapsible').collapsible();
    $('.sidenav').sidenav();

    var prevSelectedDevice = localStorage.device;
    if (prevSelectedDevice == null) {
        $('#device-content').load("empty.html");
    } else {
        $('#device-content').load("device.php", 'device=' + prevSelectedDevice);
    }

    $('body').on('click', '#deviceLabel', function() {
        selectedDevice = $(this).text();
        localStorage.setItem("device", selectedDevice);
        $('#device-content').load("device.php", 'device=' + selectedDevice);
    });
});