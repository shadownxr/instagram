enum Version {
    desktop = '',
    mobile = 'm_',
}

// #todo notification hides when changing back selection of one input while both where selected
export function notification() {
    const notification = document.querySelector('#desktop_settings #settings_notification') as HTMLDivElement;
    const m_notification = document.querySelector('#mobile_settings #settings_notification') as HTMLDivElement;

    handleEvents(Version.desktop, notification);
    handleEvents(Version.mobile, m_notification);
}

function handleEvents(version: Version, notification: HTMLDivElement) {
    const display_style = document.querySelectorAll('input[name=' + version + 'display_style]') as NodeListOf<HTMLInputElement>;
    const show_title = document.querySelectorAll('input[name=' + version + 'show_title]') as NodeListOf<HTMLInputElement>;

    addEvents(display_style, notification);
    addEvents(show_title, notification);
}

function addEvents(elements: NodeListOf<HTMLInputElement>, notification: HTMLDivElement) {
    elements.forEach((item, index) => {
        if (!item.checked) {
            item.addEventListener('click', () => {
                notification.innerHTML = '<p>Save to see changes<p>';
            });
        } else {
            item.addEventListener('click', () => {
                notification.innerHTML = '';
            });
        }
    });
}