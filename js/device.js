$(document).ready(function () {
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

    $('#variant-selector').val(localStorage.getItem(dpData.device + '_' + localStorage.getItem(dpData.device + '_version') + '_variant'));
    $('#version-selector').val(localStorage.getItem(dpData.device + '_version'));
    $('select').formSelect();

    $('#version-selector').change(function () {
        localStorage.setItem(dpData.device + '_version', dpData.versionSelected);
        setDeviceData(dpData.device);
        reloadDP();
    })

    $('#variant-selector').change(function () {
        localStorage.setItem(dpData.device + '_' + dpData.versionSelected + '_variant', dpData.variantSelected);
        setDeviceData(dpData.device);
        reloadDP();
    })

    function reloadDP() {
        loadDevicePage(dpData.device, variantSelected, versionSelected, supportedVersions, supportedVariants);
    }

});