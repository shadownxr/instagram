<div class="section__images">
    <section class="splide" id="arkon_instagram_slider" aria-label="Splide Basic HTML Example">
        <div class="splide__track">
            <ul class="splide__list">
                {foreach $images_data as $data}
                    <li class="splide__slide">
                        <a href="{$data->permalink}">
                            <img src="/img/modules/arkoninstagram/{$data->id}.jpg" alt="Instagram Gallery Photo"
                                 {if $settings->image_size != 0}height="{$settings->image_size}" width="{$settings->image_size}"
                                 {/if}
                            />
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </section>
</div>