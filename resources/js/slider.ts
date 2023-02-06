import '@splidejs/splide/css';

import Splide from '@splidejs/splide';
import UAParser from 'ua-parser-js';


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

    let parser = new UAParser();
    let device = parser.getResult();

    //#todo make it work nicely

    if(device['device']['type'] !== 'mobile'){
        new Splide( '#desktop_slider', {
            type: 'slide',
            perPage: response.images_per_gallery,
            perMove: 1,
            width: (response.images_per_gallery * response.image_size) + Number(response.gap),
            gap: Number(response.gap),
        } ).mount();
    } else {
        let m_images_per_gallery = 2;
        let m_gap = 15;
        let m_image_size = 150;

        new Splide( '.splide, #mobile_slider', {
            type: 'slide',
            perPage: m_images_per_gallery,
            perMove: 1,
            width: (m_images_per_gallery * m_image_size) + Number(m_gap),
            gap: Number(m_gap),
        } ).mount();
    }

    let m_images_per_gallery = 2;
    let m_gap = 15;
    let m_image_size = 150;

    new Splide( '#mobile_slider', {
        type: 'slide',
        perPage: m_images_per_gallery,
        perMove: 1,
        width: (m_images_per_gallery * m_image_size) + Number(m_gap),
        gap: Number(m_gap),
    } ).mount();
});
