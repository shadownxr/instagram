<div id="desktop_settings" class="active">
    {if $settings->display_style == 'slider'}
        {include file='./desktop/desktop_slider.tpl' settings=$settings images_data=$images_data}
    {elseif $settings->display_style == 'grid'}
        {include file='./desktop/desktop_grid.tpl' settings=$settings images_data=$images_data}
    {/if}
</div>