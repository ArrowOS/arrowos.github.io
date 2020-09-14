var supportedVersions;
var supportedVariants;

$(document).ready(function() {
    $('.navbar-fixed').hide();
    $('.collapsible').collapsible();
    $('.sidenav').sidenav();

    var prevSelectedDevice = $('#get_device').data('device') || localStorage.device;

    if (prevSelectedDevice == null) {
        $('#device-content').load("/empty.html");
    } else {
        supportedVersions = $('[id="deviceLabel"]:contains("' + prevSelectedDevice + '")').data('versions').split(',');
        supportedVariants = $('[id="deviceLabel"]:contains("' + prevSelectedDevice + '")').data('variants').split(',');

        var prevVariantSelected = localStorage.getItem(prevSelectedDevice + '_variant') || supportedVariants.includes('community') ? 'community' : 'official';
        var prevVersionSelected = localStorage.getItem(prevSelectedDevice + '_version') || 'arrow-10.0';

        prevVariantSelected = isStillAvailable(supportedVariants, prevVariantSelected);
        prevVersionSelected = isStillAvailable(supportedVersions, prevVersionSelected);

        supportedVariants = JSON.stringify(supportedVariants);
        supportedVersions = JSON.stringify(supportedVersions);

        $('#device-content').addClass("scale-transition scale-out");
        loadDevicePage(prevSelectedDevice, prevVariantSelected, prevVersionSelected, supportedVersions, supportedVariants);
    }

    $('body').on('click', '#deviceLabel', function() {
        $('#device-page-back').trigger('click');
        $('#device-content').addClass("scale-transition scale-out");
        selectedDevice = $(this).text();
        supportedVersions = $(this).data('versions').split(',');
        supportedVariants = $(this).data('variants').split(',');
        deviceVariant = localStorage.getItem(selectedDevice + '_variant') || supportedVariants.includes('community') ? 'community' : 'official';
        deviceVersion = localStorage.getItem(selectedDevice + '_version') || 'arrow-10.0';

        deviceVariant = isStillAvailable(supportedVariants, deviceVariant);
        deviceVersion = isStillAvailable(supportedVersions, deviceVersion);

        localStorage.setItem("device", selectedDevice);
        supportedVersions = JSON.stringify(supportedVersions);
        supportedVariants = JSON.stringify(supportedVariants);
        loadDevicePage(selectedDevice, deviceVariant, deviceVersion, supportedVersions, supportedVariants);
    });

    $('body').on('click', '#select-device', function() {
        $('.sidenav').sidenav('open');
    });

    $('body').on('click', '#reload-device', function() {
        window.location.href = '/download';
    });
});

/* Check if the previously selected version/variants are available for the device anymore
   If not then fallback to a default available value
*/
function isStillAvailable(deviceData, prevVal) {
    return (deviceData.includes(prevVal)) ? prevVal : deviceData[0];
}

function loadDevicePage(devicename, deviceVariant, deviceVersion, supportedVersions, supportedVariants) {
    $.ajax({
        url: "/device.php",
        cache: false,
        dataType: "html",
        type: "POST",
        data: {
            device: devicename,
            deviceVariant: deviceVariant,
            deviceVersion: deviceVersion,
            supportedVersions: supportedVersions,
            supportedVariants: supportedVariants
        },
        success: function(data) {
            $('#device-content').html(data);
        },
        complete: function(xhr) {
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
                    "/device404.php", {
                        device: devicename,
                        deviceVariant: deviceVariant,
                        deviceVersion: deviceVersion,
                    }
                );
            }
        }
    });
}