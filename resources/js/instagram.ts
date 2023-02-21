import './slider';
import {frontSliders, backSliders} from "./slider";
import {tabs} from "./settings/tabs";
import {notification} from "./settings/notification";

document.addEventListener("DOMContentLoaded", () => {
    tabs();
    notification();
    frontSliders();
    backSliders();
});