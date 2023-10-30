<div class="images">
    <div class="{$version}_grid_display" style="grid-template-columns: repeat({$settings->grid_column}, 1fr); grid-template-rows: repeat({$settings->grid_row}, 1fr); grid-gap: {$settings->gap}px;">
        {foreach $images_data as $key => $data}
            {if $key < $settings->images_per_gallery}
            <div class="item">
                <a href="{$data->permalink}">
                    <img loading="lazy" src="/img/modules/arkoninstagram/{$data->id}.jpg" height="{$settings->image_size}" width="{$settings->image_size}" class="{$version}_preview_images">
                </a>
            </div>
            {/if}
        {/foreach}
    </div>
</div>
