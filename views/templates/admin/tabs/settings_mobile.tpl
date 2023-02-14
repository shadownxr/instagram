<div id="mobile_settings" class="">
    {if $settings->display_style == 'slider'}
        {include file='./mobile/mobile_slider.tpl' settings=$m_settings images_data=$images_data}
    {elseif $settings->display_style == 'grid'}
        {include file='./mobile/mobile_grid.tpl' settings=$m_settings images_data=$images_data}
    {/if}
</div>