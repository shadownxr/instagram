<div class="{$version}_settings__panel panel">
    <div class="panel-heading">
        <i class="icon icon-cogs"></i>
        {if $version == $DESKTOP}{l s='Desktop Preview' mod='instagram'}{else}{l s='Mobile Preview' mod='instagram'}{/if}
        <form action="" method="POST" class="btn-default pull-right">
            <button type="submit" name="refresh"
                    class="btn btn-default pull-right">{l s='Refresh Images' mod='instagram'}</button>
        </form>
    </div>

    {if !empty($images_data)}
        <div class="instagram_display">
            <div class="section">
                {if $settings->show_title == true}
                    <div class="section__title"><h1>{$settings->title}</h1></div>
                {/if}
                <div class="section__images">
                    <section class="splide" id="preview_{$version}_slider" aria-label="Splide Basic HTML Example">
                        <div class="splide__track">
                            <ul class="splide__list">
                                {foreach $images_data as $data}
                                    <li class="splide__slide">
                                        <img src="/img/modules/arkoninstagram/{$data->id}.jpg" height="{$settings->image_size}"
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

{include file='../forms/form.tpl' settings=$settings version=$version}