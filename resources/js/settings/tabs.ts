export function tabs() {
    const desktop_button = document.querySelector('button[name="desktop_switch"]') as HTMLButtonElement;
    const mobile_button =  document.querySelector('button[name="mobile_switch"]') as HTMLButtonElement;

    const desktop_settings = document.querySelector('#desktop_settings') as HTMLDivElement;
    const mobile_settings = document.querySelector('#mobile_settings') as HTMLDivElement;

    if(desktop_button) {
        desktop_button.addEventListener('click', () => {
            desktop_settings.classList.add('active');
            desktop_button.classList.remove('btn-secondary');
            desktop_button.classList.add('btn-primary');

            mobile_settings.classList.remove('active');
            mobile_button.classList.remove('btn-primary');
            mobile_button.classList.add('btn-secondary');
        });
    }

    if(mobile_button) {
        mobile_button.addEventListener('click', () => {
            desktop_settings.classList.remove('active');
            desktop_button.classList.add('btn-secondary');
            desktop_button.classList.remove('btn-primary');

            mobile_settings.classList.add('active');
            mobile_button.classList.remove('btn-secondary');
            mobile_button.classList.add('btn-primary');
        });
    }
}
