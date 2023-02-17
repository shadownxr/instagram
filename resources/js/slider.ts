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
        width: (response[type].images_per_gallery * response[type].image_size) + parseInt(response[type].gap),
        gap: parseInt(response[type].gap),
    };

    return options;
}

export default function frontSlider() {
    $.when(fetchSettings()).done(function (response) {
        response = JSON.parse(response);

        let parser = new UAParser();
        let device_type = parser.getDevice().type as string;

        if (!(device_type === 'mobile')) {
            let options = getOptions(response, DESKTOP);
            new Splide('#desktop_slider', options).mount();
        } else {
            let mobile_options = getOptions(response, MOBILE);
            new Splide('#mobile_slider', mobile_options).mount();
        }
    });
}


jQuery(() => {
    let image_size = document.querySelector('input[name="image_size"]') as HTMLInputElement;
    let images_per_gallery = document.querySelector('input[name="images_per_gallery"]') as HTMLInputElement;
    let gap_input = document.querySelector('input[name="gap"]') as HTMLInputElement;

    let grid_display = document.querySelector('.grid_display') as HTMLDivElement;
    let grid_row_input = document.querySelector('input[name="grid_row"]') as HTMLInputElement;
    let grid_column_input = document.querySelector('input[name="grid_column"]') as HTMLInputElement;

    let perPage : number = parseInt(images_per_gallery.value);
    let size : number = parseInt(image_size.value);
    let gap : number = parseInt(gap_input.value);

    let grid_row : number = parseInt(grid_row_input.value);
    let grid_column : number = parseInt(grid_column_input.value);


    let m_image_size = document.querySelector('input[name="m_image_size"]') as HTMLInputElement;
    let m_images_per_gallery = document.querySelector('input[name="m_images_per_gallery"]') as HTMLInputElement;
    let m_gap_input = document.querySelector('input[name="m_gap"]') as HTMLInputElement;

    let m_perPage : number = parseInt(m_images_per_gallery.value);
    let m_size : number = parseInt(m_image_size.value);
    let m_gap : number = parseInt(m_gap_input.value);

    let m_grid_display = document.querySelector('.mobile_grid_display') as HTMLDivElement;
    let m_grid_row_input = document.querySelector('input[name="m_grid_row"]') as HTMLInputElement;
    let m_grid_column_input = document.querySelector('input[name="m_grid_column"]') as HTMLInputElement;

    let m_grid_row : number = parseInt(m_grid_row_input.value);
    let m_grid_column : number = parseInt(m_grid_column_input.value);

    let options = {
        type: 'slide',
        perPage: perPage,
        perMove: 1,
        width: (perPage * size) + (gap * perPage) - gap,
        autoWidth: true,
        gap: gap,
    };

    let m_options = {
        type: 'slide',
        perPage: m_perPage,
        perMove: 1,
        width: (m_perPage * m_size) + (m_gap * m_perPage) - m_gap,
        autoWidth: true,
        gap: m_gap,
    };

    if($('#preview_desktop_slider').length) {
        let desktop_slider = new Splide('#preview_desktop_slider', options);
        desktop_slider.mount();

        image_size.addEventListener("input", () => {
            size = parseInt(image_size.value);
            $('.desktop_preview_images').width(size).height(size);
            desktop_slider.options = {
                width: (perPage * size) + (gap * perPage) - gap,
            }
        });

        images_per_gallery.addEventListener("input", () => {
            perPage = parseInt(images_per_gallery.value);
            desktop_slider.options = {
                perPage: perPage,
                width: (perPage * size) + (gap * perPage) - gap,
            }
        });

        gap_input.addEventListener("input", () => {
            gap = parseInt(gap_input.value);
            desktop_slider.options = {
                width: (perPage * size) + (gap * perPage) - gap,
                gap: gap,
            }
        });
    } else {
        image_size.addEventListener("input", () => {
            size = parseInt(image_size.value);
            $('.desktop_preview_images').width(size).height(size);
        });

        gap_input.addEventListener("input", () => {
            gap = parseInt(gap_input.value);
            grid_display.style.gap = gap + 'px'
        });

        grid_row_input.addEventListener("input", () => {
           grid_row = parseInt(grid_row_input.value);
           grid_display.style.gridTemplateRows = 'repeat(' + grid_row + ', 1fr)';
        });

        grid_column_input.addEventListener("input", () => {
            grid_column = parseInt(grid_column_input.value);
            grid_display.style.gridTemplateColumns = 'repeat(' + grid_column + ', 1fr)';
        });
    }

    if($('#preview_mobile_slider').length){
        let mobile_slider = new Splide('#preview_mobile_slider', m_options);
        mobile_slider.mount();

        m_image_size.addEventListener("input", () => {
            m_size = parseInt(m_image_size.value);
            $('.mobile_preview_images').width(m_size).height(m_size);
            mobile_slider.options = {
                width: (m_perPage * m_size) + (m_gap * m_perPage) - m_gap,
            }
        });

        m_images_per_gallery.addEventListener("input", () => {
            m_perPage = parseInt(m_images_per_gallery.value);
            mobile_slider.options = {
                perPage: m_perPage,
                width: (m_perPage * m_size) + (m_gap * m_perPage) - m_gap,
            }
        });

        m_gap_input.addEventListener("input", () => {
            m_gap = parseInt(m_gap_input.value);
            mobile_slider.options = {
                width: (m_perPage * m_size) + (m_gap * m_perPage) - m_gap,
                gap: m_gap,
            }
        });

    } else {
        m_image_size.addEventListener("input", () => {
            m_size = parseInt(m_image_size.value);
            $('.mobile_preview_images').width(m_size).height(m_size);
        });

        m_gap_input.addEventListener("input", () => {
            m_gap = parseInt(m_gap_input.value);
            m_grid_display.style.gap = m_gap + 'px';
        });

        m_grid_row_input.addEventListener("input", () => {
            m_grid_row = parseInt(m_grid_row_input.value);
            m_grid_display.style.gridTemplateRows = 'repeat(' + m_grid_row + ', 1fr)';
        });

        m_grid_column_input.addEventListener("input", ()=> {
            m_grid_column = parseInt(m_grid_column_input.value);
            m_grid_display.style.gridTemplateColumns = 'repeat(' + m_grid_column + ', 1fr)';
        });
    }
});