<div class="section__images">
    <section class="splide" id="{$version}_slider" aria-label="Splide Basic HTML Example">
        <div class="splide__track">
            <ul class="splide__list">
                {foreach $images_data as $key => $data}
                    <li class="splide__slide">
                        <a href="{$data->permalink}">
                            <img src="/img/modules/instagram/{$key}.jpg" height="{$settings->image_size}" width="{$settings->image_size}"/>
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </section>
</div>