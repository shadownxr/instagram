<div class="display_header">
    <div><p>{$display_style->flex_direction}</p></div>
    {if !empty($images_url)}
        <div class="instagram_image_display" style="flex-direction:{$display_style->flex_direction};">
            {foreach from=$images_url item=url}
            <img src={$url['media_url']} />
            {/foreach}
        </div>
    {/if}
</div>