<div class="{$version}_settings {if $active == true}active{/if}">
    {if $settings->display_style == 'slider'}
        {include file='./display/slider.tpl' settings=$settings images_data=$images_data version=$version}
    {elseif $settings->display_style == 'grid'}
        {include file='./display/grid.tpl' settings=$settings images_data=$images_data version=$version}
    {/if}
</div>