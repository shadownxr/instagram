import {Version} from "../defines";

export function updateGrid(version: Version) {
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

    title_input.addEventListener("input", () => {
        const title = document.querySelector(('#' + version + '_settings') + ' .title h1') as HTMLHeadingElement;
        title.innerText = title_input.value;
    });
}