import '@splidejs/splide/css';

import Splide from '@splidejs/splide';

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

$.when(fetchSettings()).done(function(response){
    response = JSON.parse(response);

    new Splide( '.splide', {
        type: 'slide',
        perPage: response.images_per_gallery,
        perMove: 1,
        width: (response.images_per_gallery * response.image_width) + Number(response.gap),
        gap: Number(response.gap),
    } ).mount();
});
