import '@splidejs/splide/css';

import Splide from '@splidejs/splide';

const DESKTOP = 0;
const MOBILE = 1;
let url = 'https://prestashop1788.local/pl/module/instagram/ajax';

function fetchSettings() {
    return $.ajax({
        type: 'POST',
        url: url,
        cache: false,
        data: {
            method : 'test',
            ajax: true
        },
        success: function () {
        }
    });
}

function getOptions(response: any, type: number) {
    let options = {
        type: 'slide',
        perPage: response[type].images_per_gallery,
        perMove: 1,
        width: (response[type].images_per_gallery * response[type].image_size) + Number(response[type].gap),
        gap: Number(response[type].gap),
    };

    return options;
}

$.when(fetchSettings()).done(function(response){
    response = JSON.parse(response);

    let options = getOptions(response, DESKTOP);
    new Splide( '#desktop_slider', options).mount();

    let mobile_options = getOptions(response, MOBILE);
    new Splide( '#mobile_slider', mobile_options).mount();
});
