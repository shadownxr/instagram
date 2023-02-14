import '@splidejs/splide/css';

import Splide from '@splidejs/splide';
import UAParser from "ua-parser-js";

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

    let parser = new UAParser();
    let device_type = parser.getDevice().type as string;

    if(!(device_type === 'mobile')) {
        let options = getOptions(response, DESKTOP);
        new Splide('#desktop_slider', options).mount();
    } else {
        let mobile_options = getOptions(response, MOBILE);
        new Splide('#mobile_slider', mobile_options).mount();
    }
});

jQuery(() => {
    let image_size = $('input[name="image_size"]');
    let images_per_gallery = $('input[name="images_per_gallery"]');
    let gap_input = $('input[name="gap"]');

    let grid_display = $('.grid_display');
    let grid_row_input = $('input[name="grid_row"]');
    let grid_column_input = $('input[name="grid_column"]');

    let perPage: number = images_per_gallery.val() as number;
    let size: number = image_size.val() as number;
    let gap: number = gap_input.val() as number;

    let grid_row = grid_row_input.val() as number;
    let grid_column = grid_column_input.val() as number;

    let m_image_size = $('input[name="m_image_size"]');
    let m_images_per_gallery = $('input[name="m_images_per_gallery"]');
    let m_gap_input = $('input[name="m_gap"]');

    let m_perPage: number = m_images_per_gallery.val() as number;
    let m_size: number = m_image_size.val() as number;
    let m_gap: number = m_gap_input.val() as number;

    let m_grid_display = $('.m_grid_display');
    let m_grid_row_input = $('input[name="m_grid_row"]');
    let m_grid_column_input = $('input[name="m_grid_column"]');

    console.log(m_grid_row_input.length);

    let m_grid_row = m_grid_row_input.val() as number;
    let m_grid_column = m_grid_column_input.val() as number;

    let options = {
        type: 'slide',
        perPage: perPage,
        perMove: 1,
        width: (perPage * size) + (Number(gap) * perPage) - gap,
        autoWidth: true,
        gap: Number(gap),
    };

    let m_options = {
        type: 'slide',
        perPage: m_perPage,
        perMove: 1,
        width: (m_perPage * m_size) + (Number(m_gap) * m_perPage) - m_gap,
        autoWidth: true,
        gap: Number(m_gap),
    };

    //#todo mobile slider
    if($('#preview_desktop_slider').length) {
        let desktop_slider = new Splide('#preview_desktop_slider', options);
        desktop_slider.mount();

        image_size.on('input', () => {
            size = image_size.val() as number;
            $('.preview_images').width(size).height(size);
            desktop_slider.options = {
                width: (perPage * size) + (Number(gap) * perPage)- gap,
            }
        });

        images_per_gallery.on('input', () => {
            perPage = images_per_gallery.val() as number;
            desktop_slider.options = {
                perPage: perPage,
                width: (perPage * size) + (Number(gap) * perPage)- gap,
            }
        });

        gap_input.on('input', () => {
            gap = gap_input.val() as number;
            desktop_slider.options = {
                width: (perPage * size) + (Number(gap) * perPage)- gap,
                gap: Number(gap),
            }
        });
    } else {
        image_size.on('input', () => {
            size = image_size.val() as number;
            $('.desktop_preview_images').width(size).height(size);
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

    if($('#preview_mobile_slider').length){
        let mobile_slider = new Splide('#preview_mobile_slider', m_options);
        mobile_slider.mount();

        m_image_size.on('input', () => {
            m_size = m_image_size.val() as number;
            $('.mobile_preview_images').width(m_size).height(m_size);
            mobile_slider.options = {
                width: (m_perPage * m_size) + (Number(m_gap) * m_perPage) - m_gap,
            }
        });

        m_images_per_gallery.on('input', () => {
            m_perPage = m_images_per_gallery.val() as number;
            mobile_slider.options = {
                perPage: m_perPage,
                width: (m_perPage * m_size) + (Number(m_gap) * m_perPage) - m_gap,
            }

        });

        m_gap_input.on('input', () => {
            m_gap = m_gap_input.val() as number;
            mobile_slider.options = {
                width: (m_perPage * m_size) + (Number(m_gap) * m_perPage) - m_gap,
                gap: Number(m_gap),
            }
        });
    } else {
        m_image_size.on('input', () => {
            m_size = m_image_size.val() as number;
            $('.mobile_preview_images').width(m_size).height(m_size);
        });

        m_gap_input.on('input', () => {
            m_gap = m_gap_input.val() as number;
            m_grid_display.css('grid-gap', Number(m_gap) + 'px');
        });

        m_grid_row_input.on('input', () => {
            m_grid_row = m_grid_row_input.val() as number;
            m_grid_display.css('grid-template-rows', 'repeat(' + m_grid_row + ', 1fr)');
        });

        m_grid_column_input.on('input', ()=> {
            m_grid_column = m_grid_column_input.val() as number;
            m_grid_display.css('grid-template-columns', 'repeat(' + m_grid_column + ', 1fr)');
        });
    }
});