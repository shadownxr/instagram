enum Version {
    desktop = '',
    mobile = 'm_',
}

export function notification() {
    const notification = document.querySelector('#desktop_settings #settings_notification') as HTMLDivElement;
    const m_notification = document.querySelector('#mobile_settings #settings_notification') as HTMLDivElement;

    handleEvents(Version.desktop, notification);
    handleEvents(Version.mobile, m_notification);
}

function handleEvents(version: Version, notification: HTMLDivElement) {
    const display_settings = document.querySelectorAll('input[name=' + version + 'display_style], input[name=' + version + 'show_title]') as NodeListOf<HTMLInputElement>;

    addEvents(display_settings, notification);
}

class RadioButton {
    radio_inputs : NodeListOf<HTMLInputElement>;
    state: Boolean = false;

    constructor(radio_inputs: NodeListOf<HTMLInputElement>) {
        this.radio_inputs = radio_inputs;
    }

    handle(){
        this.radio_inputs.forEach((item, index) => {
            item.addEventListener('change', () => {
                this.setState();
            });
        });
    }

    setState(){
        this.state = !this.state;
    }
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