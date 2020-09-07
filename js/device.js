$(document).ready(function() {
    $('select').formSelect();
    var dpData = {
        get device() {
            return localStorage.getItem('device');
        },
        get variantSelected() {
            return $('#variant-selector').val();
        },
        get versionSelected() {
            return $('#version-selector').val();
        }
    };

    $('#variant-selector').val(localStorage.getItem(dpData.device + '_variant'));
    $('#version-selector').val(localStorage.getItem(dpData.device + '_version'));
    $('select').formSelect();

    $('#version-selector').change(function() {
        reloadDP();
    })

    $('#variant-selector').change(function() {
        reloadDP();
    })

    function reloadDP() {
        loadDevicePage(dpData.device, dpData.variantSelected, dpData.versionSelected, supportedVersions);
    }

});