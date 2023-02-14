<div class="display">
    {if !empty($images_data)}
        <div class="instagram_image_display">
            <div class="section">
                {if $settings->show_title == true}
                    <div class="title"><h1>{$settings->title}</h1></div>
                {/if}
                {if $settings->display_style == 'slider'}
                    {include file='./mobile/mobile_slider.tpl' images_data=$images_data settings=$settings}
                {elseif $settings->display_style == 'grid'}
                    {include file='./mobile/mobile_grid.tpl' images_data=$images_data settings=$settings}
                {/if}
            </div>
        </div>
    {/if}
</div>