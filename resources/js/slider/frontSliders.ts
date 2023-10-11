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

function getOptions(response: any) {
    const desktop_images_per_gallery = parseInt(response[DESKTOP].images_per_gallery);
    const desktop_gap = parseInt(response[DESKTOP].gap);
    const desktop_image_size = parseFloat(response[DESKTOP].image_size);

    const mobile_images_per_gallery = parseInt(response[MOBILE].images_per_gallery);
    const mobile_gap = parseInt(response[MOBILE].gap);
    const mobile_image_size = parseFloat(response[MOBILE].image_size);

    let options = {
        type: 'slide',
        perPage: desktop_images_per_gallery,
        perMove: 1,
        gap: desktop_gap,
        width: undefined as number,
        breakpoints: {
            992: {
                perPage: mobile_images_per_gallery,
                gap: mobile_gap,
                width: undefined as number,

            }
        },
    }

    if(desktop_image_size !== 0){
        options.width = (desktop_images_per_gallery * desktop_image_size) + (desktop_gap * desktop_images_per_gallery) - desktop_gap;
    }

    if(mobile_image_size !== 0){
        options.breakpoints['992'].width = (mobile_images_per_gallery * mobile_image_size) + (mobile_gap * mobile_images_per_gallery) - mobile_gap;
    }

    return options;
}

export function frontSliders() {
    fetchSettings().then((response) => {
        const options =  getOptions(response);
        new Splide('#arkon_instagram_slider', options).mount();
    });
}