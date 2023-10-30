<div class="{$version}_settings__panel panel">
    <div class="panel-heading">
        <i class="icon icon-cogs"></i>
        {if $version == $DESKTOP}{l s='Desktop Preview' mod='instagram'}{else}{l s='Mobile Preview' mod='instagram'}{/if}
        <form action="" method="POST" class="btn-default pull-right">
            <button type="submit" name="refresh"
                    class="btn btn-default pull-right">{l s='Refresh Images' mod='arkoninstagram'}</button>
        </form>
    </div>

    <div class="display">
        {if !empty($images_data)}
            <div class="instagram_display">
                <div class="section">
                    {if $settings->show_title == true}
                        <div class="section__title"><h1>{$settings->title}</h1></div>
                    {/if}

                    <div class="section__images">
                        <div class="{$version}_grid_display"
                             style="grid-template-columns: repeat({$settings->grid_column}, 1fr); grid-template-rows: repeat({$settings->grid_row}, 1fr); grid-gap: {$settings->gap}px;">
                            {foreach $images_data as $key => $data}
                                {if $key < $settings->images_per_gallery}
                                <div class="item">
                                    <img src="/img/modules/arkoninstagram/{$data->id}.jpg" height="{$settings->image_size}"
                                         width="{$settings->image_size}" class="{$version}_preview_images"
                                         alt="Instagram image"
                                    />
                                </div>
                                {/if}
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>

{include file='../forms/form.tpl' settings=$settings version=$version}