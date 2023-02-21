import './slider';
import {frontSliders, backSliders} from "./slider";
import {tabs} from "./settings/tabs";

document.addEventListener("DOMContentLoaded", () => {
    tabs();
    frontSliders();
    backSliders();
});