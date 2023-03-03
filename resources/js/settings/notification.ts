enum Version {
    desktop = 'desktop',
    mobile = 'mobile',
}

export function notification() {
    const notification = document.querySelector('.desktop_settings #settings_notification') as HTMLDivElement;
    const m_notification = document.querySelector('.mobile_settings #settings_notification') as HTMLDivElement;

    handleEvents(Version.desktop, notification);
    handleEvents(Version.mobile, m_notification);
}

function handleEvents(version: Version, notification: HTMLDivElement) {
    const display_settings = document.querySelectorAll('input[name=' + version + '_display_style], input[name=' + version + '_show_title]') as NodeListOf<HTMLInputElement>;

    addEvents(display_settings, notification);
}

function addEvents(elements: NodeListOf<HTMLInputElement>, notification: HTMLDivElement) {
    let change : Boolean[] = [false, false];
    elements.forEach((item, index) => {
        let radio_id = Math.floor(index/2);
        item.addEventListener('change', () => {
            change[radio_id] = !change[radio_id];
            if(JSON.stringify(change) !== JSON.stringify([false, false])){
                notification.innerHTML = '<p>Save to see changes</p>';
            } else {
                notification.innerHTML = '';
            }
        });
    });
}