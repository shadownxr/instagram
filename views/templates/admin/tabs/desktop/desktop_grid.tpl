<div class="panel" style="max-height:500px;overflow:scroll;">
    <div class="panel-heading">
        <i class="icon icon-cogs"></i>
        {l s='Desktop Preview' mod='instagram'}
        <form action="" method="POST" class="btn-default pull-right">
            <button type="submit" name="refresh" class="btn btn-default pull-right">Refresh Images</button>
        </form>
    </div>

    <div class="display">
        {if !empty($images_data)}
            <div class="instagram_image_display">
                <div class="section">
                    {if $settings->show_title == true}
                        <div class="title"><h1>{$settings->title}</h1></div>
                    {/if}

                    <div class="images">
                        <div class="grid_display"
                             style="grid-template-columns: repeat({$settings->grid_column}, 1fr); grid-template-rows: repeat({$settings->grid_row}, 1fr); grid-gap: {$settings->gap}px;">
                            {foreach from=$images_data item=data}
                                <div class="item">
                                    <img src={$data['image_url']} height="{$settings->image_size}"
                                         width="{$settings->image_size}" class="desktop_preview_images"
                                         alt="Instagram image"
                                    />
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>

{include file='../forms/form.tpl' settings=$settings version=$DESKTOP}