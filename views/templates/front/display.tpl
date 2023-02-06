<div class="display">
    {if !empty($images_data)}
        <div class="instagram_image_display">
            <div class="section">
            {if $display_style->show_title == true}
                <div class="title"><h1>{$display_style->title}</h1></div>
            {/if}
                <div class="images">
                    <section class="splide" aria-label="Splide Basic HTML Example">
                        <div class="splide__track">
                            <ul class="splide__list">
                                {foreach from=$images_data item=data}
                                    <li class="splide__slide">
                                        <img src={$data['image_url']} height="{$display_style->image_size}" width="{$display_style->image_size}" 
                                            style="margin:{$display_style->image_margin}px;
                                            border-radius:{$display_style->image_border_radius}px;
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


