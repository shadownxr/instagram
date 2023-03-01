import Splide from '@splidejs/splide';
import {Version} from "../defines";

export class AdminSliders {
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