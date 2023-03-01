<div id="{$version}_settings" class="{if $active == true}active{/if}">
    {if $settings->display_style == 'slider'}
        {include file='./slider.tpl' settings=$settings images_data=$images_data version=$version}
    {elseif $settings->display_style == 'grid'}
        {include file='./grid.tpl' settings=$settings images_data=$images_data version=$version}
    {/if}
</div>