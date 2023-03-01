import {Version} from "./defines";
import {updateGrid} from "./grid/grid";
import {AdminSliders} from "./slider/adminSliders";
export function adminEvents(){
    const display_style = document.querySelector('input[name=' + Version.desktop + '_display_style]:checked') as HTMLInputElement;
    const m_display_style = document.querySelector('input[name=' + Version.mobile + '_display_style]:checked') as HTMLInputElement;

    if(display_style) {
        if (display_style.value == 'slider') {
            new AdminSliders(Version.desktop);
        } else if (display_style.value == 'grid') {
            updateGrid(Version.desktop);
        }
    }

    if(m_display_style) {
        if (m_display_style.value == 'slider') {
            new AdminSliders(Version.mobile);
        } else if (m_display_style.value == 'grid') {
            updateGrid(Version.mobile);
        }
    }
}