import Splide from '@splidejs/splide';
import UAParser from "ua-parser-js";

const DESKTOP = 0;
const MOBILE = 1;
let url = 'https://prestashop1788.local/pl/module/instagram/ajax';

enum Version {
    desktop = 'desktop',
    mobile = 'mobile'
}

async function fetchSettings() {
    const data = { ajax: true };
    const response =  await fetch(url, {
        method: "POST",
        mode: "same-origin",
        cache: "no-cache",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify(data),
    });
    return response.json();
}

function getOptions(response: any, type: number) {
    return {
        type: 'slide',
        perPage: response[type].images_per_gallery,
        perMove: 1,
        width: (response[type].images_per_gallery * response[type].image_size) + parseInt(response[type].gap),
        gap: parseInt(response[type].gap),
    };
}

export function frontSliders() {
    fetchSettings().then((response) => {
        let parser = new UAParser();
        let device_type = parser.getDevice().type as string;

        if(response[DESKTOP].display_style === 'slider' && !(device_type === 'mobile')){
            let options = getOptions(response, DESKTOP);
            new Splide('#desktop_slider', options).mount();
        }

        if(response[MOBILE].display_style === 'slider' && (device_type === 'mobile')){
            let mobile_options = getOptions(response, MOBILE);
            new Splide('#mobile_slider', mobile_options).mount();
        }
    });
}

export function backSliders() {
    const display_style = document.querySelector('input[name=' + Version.desktop + '_display_style]:checked') as HTMLInputElement;
    const m_display_style = document.querySelector('input[name=' + Version.mobile + '_display_style]:checked') as HTMLInputElement;

    if(display_style) {
        if (display_style.value == 'slider') {
            new Slider(Version.desktop);
        } else if (display_style.value == 'grid') {
            updateGrid(Version.desktop);
        }
    }

    if(m_display_style) {
        if (m_display_style.value == 'slider') {
            new Slider(Version.mobile);
        } else if (display_style.value == 'grid') {
            updateGrid(Version.mobile);
        }
    }
}

class Slider {
    version: Version;
    slider: Splide;
    image_size_input: HTMLInputElement;
    images_per_gallery: HTMLInputElement;
    gap_input: HTMLInputElement;
    title_input: HTMLInputElement;

    perPage: number;
    size: number;
    gap: number;

    constructor(version: Version) {
        this.version = version;
        this.image_size_input = document.querySelector('input[name=' + this.version + '_image_size]') as HTMLInputElement;
        this.images_per_gallery = document.querySelector('input[name=' + this.version + '_images_per_gallery]') as HTMLInputElement;
        this.gap_input = document.querySelector('input[name=' + this.version + '_gap]') as HTMLInputElement;
        this.title_input = document.querySelector('input[name='+ this.version +'_title]') as HTMLInputElement;

        this.perPage = parseInt(this.images_per_gallery.value);
        this.size = parseInt(this.image_size_input.value);
        this.gap = parseInt(this.gap_input.value);

        let options = {
            type: 'slide',
            perPage: this.perPage,
            perMove: 1,
            width: (this.perPage * this.size) + (this.gap * this.perPage) - this.gap,
            gap: this.gap,
        };

        this.slider = new Splide('#preview_'+ this.version +'_slider', options);
        this.slider.mount();
        this.inputEvents();
    }

    public inputEvents() {
        this.image_size_input.addEventListener("input", () => {
            this.size = parseInt(this.image_size_input.value);
            const images = document.querySelectorAll('.' + this.version + '_preview_images') as NodeListOf<HTMLImageElement>;
            images.forEach(image => {
                image.width = this.size;
                image.height = this.size;
            });
            this.slider.options = {
                width: (this.perPage * this.size) + (this.gap * this.perPage) - this.gap,
            }
        });

        this.images_per_gallery.addEventListener("input", () => {
            this.perPage = parseInt(this.images_per_gallery.value);
            this.slider.options = {
                perPage: this.perPage,
                width: (this.perPage * this.size) + (this.gap * this.perPage) - this.gap,
            }
        });

        this.gap_input.addEventListener("input", () => {
            this.gap = parseInt(this.gap_input.value);
            this.slider.options = {
                width: (this.perPage * this.size) + (this.gap * this.perPage) - this.gap,
                gap: this.gap,
            }
        });

        this.title_input.addEventListener("input", () => {
           const title = document.querySelector(('#' + this.version + '_settings') + ' .title h1') as HTMLHeadingElement;
           title.innerText = this.title_input.value;
        });
    }
}

function updateGrid(version: Version) {
    const image_size_input = document.querySelector('input[name=' + version + '_image_size]') as HTMLInputElement;
    const gap_input = document.querySelector('input[name=' + version + '_gap]') as HTMLInputElement;
    const grid_row_input = document.querySelector('input[name=' + version + '_grid_row]') as HTMLInputElement;
    const grid_column_input = document.querySelector('input[name=' + version + '_grid_column]') as HTMLInputElement;
    const title_input = document.querySelector('input[name='+ version +'_title]') as HTMLInputElement;

    const grid_display = document.querySelector('.' + version + '_grid_display') as HTMLDivElement;

    let size: number = parseInt(image_size_input.value);
    let gap: number = parseInt(gap_input.value);

    let grid_row: number;
    if (grid_row_input) {
        grid_row = parseInt(grid_row_input.value);
    }

    let grid_column: number;
    if (grid_column_input) {
        grid_column = parseInt(grid_column_input.value);
    }

    image_size_input.addEventListener("input", () => {
        size = parseInt(image_size_input.value);
        const images = document.querySelectorAll('.' + version + '_preview_images') as NodeListOf<HTMLImageElement>;
        images.forEach(image => {
            image.width = size;
            image.height = size;
        });
    });

    gap_input.addEventListener("input", () => {
        gap = parseInt(gap_input.value);
        grid_display.style.gap = gap + 'px';
    });

    grid_row_input.addEventListener("input", () => {
        grid_row = parseInt(grid_row_input.value);
        grid_display.style.gridTemplateRows = 'repeat(' + grid_row + ', 1fr)';
    });

    grid_column_input.addEventListener("input", () => {
        grid_column = parseInt(grid_column_input.value);
        grid_display.style.gridTemplateColumns = 'repeat(' + grid_column + ', 1fr)';
    });

    console.log(title_input);

    title_input.addEventListener("input", () => {
       const title = document.querySelector(('#' + version + '_settings') + ' .title h1') as HTMLHeadingElement;
       title.innerText = title_input.value;
    });
}