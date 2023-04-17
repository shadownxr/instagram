<div class="images">
    <div class="{$version}_grid_display" style="grid-template-columns: repeat({$settings->grid_column}, 1fr); grid-template-rows: repeat({$settings->grid_row}, 1fr); grid-gap: {$settings->gap}px;">
        {foreach from=$images_data item=data}
            <div class="item">
                <a href="{$data['permalink']}">
                    <img src={$data['image_url']} height="{$settings->image_size}" width="{$settings->image_size}" class="{$version}_preview_images">
                </a>
            </div>
        {/foreach}
    </div>
</div>
