<div class="images">
    <div class="grid_display" style="grid-template-columns: repeat({$settings->grid_column}, 1fr); grid-template-rows: repeat({$settings->grid_row}, 1fr); grid-gap: {$settings->gap}px;">
        {foreach from=$images_data item=data}
            <div class="item">
                <img src={$data['image_url']} height="{$settings->image_size}" width="{$settings->image_size}" class="preview_images">
            </div>
        {/foreach}
    </div>
</div>
