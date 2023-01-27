$(document).ready(function() {
    $('input[name="display_direction"]').click(function(){
        var radioValue = $('input[name="display_direction"]:checked').val();

        $('.instagram_image_display').css('flex-direction',radioValue);
    });

    $('input[name="image_width"]').on('input',function(){
        var width = $('input[name="image_width"]').val();
        $('.images').width(width);
    });

    $('input[name="image_height"]').on('input',function(){
        var height = $('input[name="image_height"]').val();
        $('.images').height(height);
    });

    $('input[name="image_margin"]').on('input',function(){
        var margin = $('input[name="image_margin"]').val();
        $('.images').css('margin',margin.concat('px'));
    });

    $('input[name="image_border_radius"]').on('input',function(){
        var border_radius = $('input[name="image_border_radius"]').val();
        $('.images').css('border-radius',border_radius.concat('px'));
    });

    $('select[name="description_alignment"]').on('input',function(){
        var description_alignment = $('select[name="description_alignment"]').val();
        $('.image').css('flex-direction',description_alignment);
    });
})

