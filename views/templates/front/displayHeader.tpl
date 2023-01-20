<div>
    {if !empty($images_url)}
        <div class="instagram_image_display">
            {foreach from=$images_url item=url}
            <img src={$url['media_url']} />
            {/foreach}
        </div>
    {/if}
</div>