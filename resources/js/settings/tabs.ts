jQuery(() => {
    let desktop_button = $('button[name="desktop_switch"]');
    let mobile_button = $('button[name="mobile_switch"]');

    let desktop_settings = $('#desktop_settings');
    let mobile_settings = $('#mobile_settings');

    desktop_button.on('click', () => {
        desktop_settings.addClass('active');
        mobile_settings.removeClass('active');
    });

    mobile_button.on('click', () => {
        desktop_settings.removeClass('active');
        mobile_settings.addClass('active');
    });
});

