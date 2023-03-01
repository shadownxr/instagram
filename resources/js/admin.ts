import '@splidejs/splide/css';
import {tabs} from "./settings/tabs";
import {notification} from "./settings/notification";
import {adminEvents} from "./events";

document.addEventListener("DOMContentLoaded", () => {
    tabs();
    notification();
    adminEvents();
});