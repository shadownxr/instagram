import UAParser from "ua-parser-js";
import Splide from "@splidejs/splide";

const DESKTOP = 0;
const MOBILE = 1;

async function fetchSettings() {
    const data = { ajax: true };
    const response =  await fetch(window.instagram_ajax_url, {
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
    const images_per_gallery = parseInt(response[type].images_per_gallery);
    const gap = parseInt(response[type].gap);
    const image_size = parseFloat(response[type].image_size);

    if(image_size === 0){
        return {
            type: 'slide',
            perPage: images_per_gallery,
            perMove: 1,
            gap: gap,
        }
    } else {
        return {
            type: 'slide',
            perPage: images_per_gallery,
            perMove: 1,
            width: (images_per_gallery * image_size) + (gap * images_per_gallery) - gap,
            gap: gap,
        };
    }
}

export function frontSliders() {
    fetchSettings().then((response) => {
        let parser = new UAParser();
        let device_type = parser.getDevice().type as string;

        if(response[DESKTOP].display_style === 'slider' && !(device_type === 'mobile')){
            let options = getOptions(response, DESKTOP);
            new Splide('#arkon_instagram_desktop_slider', options).mount();
        }

        if(response[MOBILE].display_style === 'slider' && (device_type === 'mobile')){
            let mobile_options = getOptions(response, MOBILE);
            new Splide('#arkon_instagram_mobile_slider', mobile_options).mount();
        }
    });
}