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

jQuery(() => {
    let image_size = $('input[name="image_size"]');
    let images_per_gallery = $('input[name="images_per_gallery"]');
    let gap_input = $('input[name="gap"]');

    let grid_display = $('.grid_display');
    let grid_row_input = $('input[name="grid_row"]');
    let grid_column_input = $('input[name="grid_column"]');

    let perPage: number = 2;
    let size: number = image_size.val() as number;
    let gap: number = 15;

    let grid_row = 2;
    let grid_column = 2;

    let options = {
        type: 'slide',
        perPage: perPage,
        perMove: 1,
        width: (perPage * size) + Number(gap),
        autoWidth: true,
        gap: gap,
    };

    //#todo mobile slider
    if($('#preview_desktop_slider').length) {
        let desktop_slider = new Splide('#preview_desktop_slider', options);
        desktop_slider.mount();

        image_size.on('input', () => {
            size = image_size.val() as number;
            $('.preview_images').width(size).height(size);
            desktop_slider.options = {
                width: (2 * size) + Number(gap),
            }
        });

        images_per_gallery.on('input', () => {
            perPage = images_per_gallery.val() as number;
            desktop_slider.options = {
                perPage: perPage,
                width: (perPage * size) + Number(gap),
            }
        });

        gap_input.on('input', () => {
            gap = gap_input.val() as number;
            desktop_slider.options = {
                width: (perPage * size) + Number(gap),
                gap: Number(gap),
            }
        });
    } else {
        image_size.on('input', () => {
            size = image_size.val() as number;
            $('.preview_images').width(size).height(size);
        });

        gap_input.on('input', () => {
            gap = gap_input.val() as number;
            grid_display.css('grid-gap', Number(gap) + 'px');
        });

        grid_row_input.on('input', () => {
           grid_row = grid_row_input.val() as number;
           grid_display.css('grid-template-rows', 'repeat(' + grid_row + ', 1fr)');
        });

        grid_column_input.on('input', ()=> {
            grid_column = grid_column_input.val() as number;
            grid_display.css('grid-template-columns', 'repeat(' + grid_column + ', 1fr)');
        });
    }
});