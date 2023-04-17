<div class="section__images">
    <section class="splide" id="{$version}_slider" aria-label="Splide Basic HTML Example">
        <div class="splide__track">
            <ul class="splide__list">
                {foreach from=$images_data item=data}
                    <li class="splide__slide">
                        <a href="{$data['permalink']}">
                            <img src={$data['image_url']} height="{$settings->image_size}" width="{$settings->image_size}"/>
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </section>
</div>