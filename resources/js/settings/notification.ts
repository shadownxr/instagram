export function notification(){
    const display_style = document.querySelectorAll('input[name="display_style"]') as NodeListOf<HTMLInputElement>;
    const show_title = document.querySelectorAll('input[name="show_title"]') as NodeListOf<HTMLInputElement>;

    const notification = document.querySelector('#settings_notification') as HTMLDivElement;

    display_style.forEach((item) => {
        if(item.checked == false) {
            item.addEventListener('click', () => {
                notification.innerHTML = '<p>Save to see changes<p>';
            });
        } else {
            item.addEventListener('click', () => {
                notification.innerHTML = '';
            });
        }
    });

    show_title.forEach((item) => {
        if(item.checked == false) {
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