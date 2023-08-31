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