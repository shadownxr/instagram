<div class="panel" style="max-height:500px;overflow:scroll;">
    <div class="panel-heading">
        <i class="icon icon-cogs"></i>
        {if $version == $DESKTOP}{l s='Desktop Preview' mod='instagram'}{else}{l s='Mobile Preview' mod='instagram'}{/if}
        <form action="" method="POST" class="btn-default pull-right">
            <button type="submit" name="refresh"
                    class="btn btn-default pull-right">{l s='Refresh Images' mod='instagram'}</button>
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
                        <section class="splide" id="preview_{$version}_slider" aria-label="Splide Basic HTML Example">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    {foreach from=$images_data item=data}
                                        <li class="splide__slide">
                                            <img src={$data['image_url']} height="{$settings->image_size}"
                                                 width="{$settings->image_size}" class="{$version}_preview_images"
                                                 alt="Instagram image"
                                            />
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>

{include file='./forms/form.tpl' settings=$settings version=$version}