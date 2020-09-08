var supportedVersions;
var supportedVariants;

$(document).ready(function() {
    $('.navbar-fixed').hide();
    $('.collapsible').collapsible();
    $('.sidenav').sidenav();

    var prevSelectedDevice = localStorage.device;
    var prevVariantSelected = localStorage.getItem(prevSelectedDevice + '_variant');
    var prevVersionSelected = localStorage.getItem(prevSelectedDevice + '_version');

    if (prevSelectedDevice == null) {
        $('#device-content').load("empty.html");
    } else {
        $('#device-content').addClass("scale-transition scale-out");
        supportedVersions = JSON.stringify($('[id="deviceLabel"]:contains("' + prevSelectedDevice + '")').data('versions').split(','));
        supportedVariants = JSON.stringify($('[id="deviceLabel"]:contains("' + prevSelectedDevice + '")').data('variants').split(','));
        loadDevicePage(prevSelectedDevice, prevVariantSelected, prevVersionSelected, supportedVersions, supportedVariants);
    }

    $('body').on('click', '#deviceLabel', function() {
        $('#device-page-back').trigger('click');
        $('#device-content').addClass("scale-transition scale-out");
        selectedDevice = $(this).text();
        supportedVersions = $(this).data('versions').split(',');
        supportedVariants = $(this).data('variants').split(',');
        deviceVariant = (supportedVariants.length >= 1) ? supportedVariants[0] : "official";
        deviceVariant = localStorage.getItem(selectedDevice + '_variant') || deviceVariant;
        deviceVersion = (supportedVersions.length >= 1) ? supportedVersions[0] : null;
        deviceVersion = localStorage.getItem(selectedDevice + '_version') || deviceVersion;
        localStorage.setItem("device", selectedDevice);
        supportedVersions = JSON.stringify(supportedVersions);
        supportedVariants = JSON.stringify(supportedVariants);
        loadDevicePage(selectedDevice, deviceVariant, deviceVersion, supportedVersions, supportedVariants);
    });

    $('body').on('click', '#select-device', function() {
        $('.sidenav').sidenav('open');
    });

    $('body').on('click', '#reload-device', function() {
        location.reload();
    });
});

function loadDevicePage(devicename, deviceVariant, deviceVersion, supportedVersions, supportedVariants) {
    $('#device-content').load(
        "device.php", {
            device: devicename,
            deviceVariant: deviceVariant,
            deviceVersion: deviceVersion,
            supportedVersions: supportedVersions,
            supportedVariants: supportedVariants
        },
        function(response, status, xhr) {
            if (xhr.status === 200) {
                $('#device-content').removeClass("scale-transition scale-out");
                $('#device-content').addClass("scale-transition");
                $(window).scrollTop(0);

                localStorage.setItem(devicename + '_variant', deviceVariant);
                localStorage.setItem(devicename + '_version', deviceVersion);
                localStorage.setItem(devicename + '_supportedVersions', supportedVersions);
                localStorage.setItem(devicename + '_supportedVariants', supportedVariants);
            } else {
                $('#device-content').removeClass("scale-transition scale-out");
                $('#device-content').addClass("scale-transition");
                $('#device-content').load(
                    "device404.php", {
                        device: devicename,
                        deviceVariant: deviceVariant,
                        deviceVersion: deviceVersion,
                    }
                );
            }
        }
    );
}