jQuery(() => {
    let desktop_button = $('button[name="desktop_switch"]');
    let mobile_button = $('button[name="mobile_switch"]');

    let desktop_settings = $('#desktop_settings');
    let mobile_settings = $('#mobile_settings');

    desktop_button.on('click', () => {
        desktop_settings.addClass('active');
        desktop_button.removeClass('btn-secondary').addClass('btn-primary');
        mobile_settings.removeClass('active');
        mobile_button.removeClass('btn-primary').addClass('btn-secondary');
    });

    mobile_button.on('click', () => {
        desktop_settings.removeClass('active');
        desktop_button.removeClass('btn-primary').addClass('btn-secondary');
        mobile_settings.addClass('active');
        mobile_button.removeClass('btn-secondary').addClass('btn-primary');
    });
});

