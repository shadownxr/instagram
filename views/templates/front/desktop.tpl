<div class="display">
    {if !empty($images_data)}
        <div class="instagram_image_display">
            <div class="section">
            {if $settings->show_title == true}
                <div class="title"><h1>{$settings->title}</h1></div>
            {/if}
            {* #todo settings->display_style *}
            {if $settings->display_style == 'slider'}
                {include file='./desktop/desktop_slider.tpl' images_data=$images_data settings=$settings}
            {elseif $settings->display_style == 'grid'}
                {include file='./desktop/desktop_grid.tpl' images_data=$images_data settings=$settings}
            {/if}
            </div>
        </div>
    {/if}
</div>