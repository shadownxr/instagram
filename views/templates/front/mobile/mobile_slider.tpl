<div class="display">
    {if !empty($images_data)}
        <div class="instagram_image_display">
            <div class="section">
                {if $settings->show_title == true}
                    <div class="title"><h1>{$settings->title}</h1></div>
                {/if}
                <div class="images">
                    <section class="splide" id="mobile_slider" aria-label="Splide Basic HTML Example">
                        <div class="splide__track">
                            <ul class="splide__list">
                                {foreach from=$images_data item=data}
                                    <li class="splide__slide">
                                        <img src={$data['image_url']} height="{$settings->image_size}" width="{$settings->image_size}" class="mobile_preview_images"
                                             style="margin:{$settings->image_margin}px;
                                                     border-radius:{$settings->image_border_radius}px;
                                                     "/>
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