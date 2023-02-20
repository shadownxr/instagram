import './slider';
import './settings/tabs.ts';
import {frontSliders, backSliders} from "./slider";

document.addEventListener("DOMContentLoaded", () => {
    frontSliders();
    backSliders();
});