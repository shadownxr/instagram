<div id="mobile_settings" class="">
    <div class="panel" style="max-height:500px;overflow:scroll;">
        <h3><i class="icon icon-cogs"></i> {l s='Mobile Preview' mod='instagram'}</h3>
        
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
                                            {if !Context::getContext()->isMobile()}
                                                <img src={$data['image_url']} height="{$settings->image_size}" width="{$settings->image_size}" 
                                                    style="margin:{$settings->image_margin}px;
                                                    border-radius:{$settings->image_border_radius}px;
                                                "/>
                                            {else}
                                                <img src={$data['image_url']} height="150px" width="150px" 
                                                    style="margin:{$settings->image_margin}px;
                                                    border-radius:{$settings->image_border_radius}px;
                                                "/>
                                            {/if}

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

    <form action="" method="POST">
        <div class="panel">
            <h3><i class="icon icon-cogs"></i> {l s='Image settings' mod='instagram'}</h3>
            
            <div class="form-group">
                    
            <label class="form-control-label" for="display_direction">Change display direction</label>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="m_display_direction"
                    id="m_display_direction_column"
                    value="column"
                    {if $settings->flex_direction == "column"}
                        checked
                    {/if}
                    />
                    <i class="form-check-round">Column</i>
                </label>
                </div>

                <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="m_display_direction"
                    id="m_display_direction_row"
                    value="row"
                    {if $settings->flex_direction  == "row"}
                        checked
                    {/if}
                    />
                    <i class="form-check-round">Row</i>
                </label>
            </div>

            <div
                class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
                data-max="1920"
                data-min="1"
                data-label-max="Maximum:1920"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="image_size"
                    >Size of image</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->image_size}" id="m_image_size" name="m_image_size"/>
                </div>
            </div>

            <div
                class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
                data-max="50"
                data-min="0"
                data-label-max="Maximum:50"
                data-label-min="Minimum:0"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="image_margin"
                    >Margin of image</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->image_margin}" id="m_image_margin" name="m_image_margin"/>
                </div>
            </div>

            <div
                class="form-group prestasho-number-input prestasho-number-input-enable-arrows"
                data-max="50"
                data-min="0"
                data-label-max="Maximum:50"
                data-label-min="Minimum:0"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="image_border_radius"
                    >Border radius of image</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->image_border_radius}" id="m_image_border_radius" name="m_image_border_radius"/>
                </div>
            </div>

            <label class="form-control-label"
                    >Show description: </label
                >
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="m_show_description"
                    id="m_show_description_true"
                    value="1"
                    {if $settings->show_description == true}
                        checked
                    {/if}
                    />
                    <i class="form-check-round"></i>
                    Yes
                </label>
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="m_show_description"
                    id="m_show_description_false"
                    value="0"
                    {if $settings->show_description == false}
                        checked
                    {/if}
                    />
                    <i class="form-check-round"></i>
                    No
                </label>
            </div>
            
            <label class="form-control-label"
                    >Description alignment: </label
                >
            <div class="form-select">
                <select class="form-control custom-select" name="m_description_alignment">
                    <option value="column" {if $settings->description_alignment == 'column'}selected{/if}>Bottom</option>
                    <option value="row-reverse" {if $settings->description_alignment == 'row-reverse'}selected{/if}>Left</option>
                    <option value="column-reverse" {if $settings->description_alignment == 'column-reverse'}selected{/if}>Top</option>
                    <option value="row" {if $settings->description_alignment == 'row'}selected{/if}>Right</option>
                </select>
            </div>

            </div>
            <button type="submit" name="save_mobile_settings">Save</button>
        </div>

        <div class="panel">
            <h3><i class="icon icon-cogs"></i> {l s='Gallery settings' mod='instagram'}</h3>

            <label class="form-control-label">Select hook to display images: </label>
            <div class="form-select">
                <select class="form-control custom-select" name="m_display_hook">
                    {foreach from=$display_hooks item=display_hook}
                        <option value="{$display_hook['name']}" {if $settings->hook == $display_hook['name']} selected {/if}>{$display_hook['name']}</option>
                    {/foreach}
                </select>
            </div>

            <label class="form-control-label"
                    >Show title: </label
                >
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="m_show_title"
                    id="m_show_title_true"
                    value="1"
                    {if $settings->show_title == true}
                        checked
                    {/if}
                    />
                    <i class="form-check-round"></i>
                    Yes
                </label>
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="m_show_title"
                    id="m_show_title_false"
                    value="0"
                    {if $settings->show_title == false}
                        checked
                    {/if}
                    />
                    <i class="form-check-round"></i>
                    No
                </label>
            </div>

            <div
                class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
                data-max="1920"
                data-min="1"
                data-label-max="Maximum:1920"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="max_images_fetched"
                    >Maximum number of images fetched from your Instagram gallery</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->max_images_fetched}" id="m_max_images_fetched" name="m_max_images_fetched"/>
                </div>
            </div>

            <div
                class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
                data-max="10"
                data-min="1"
                data-label-max="Maximum:10"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="max_images_fetched"
                    >Number of images display at one time</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->images_per_gallery}" id="m_images_per_gallery" name="m_images_per_gallery"/>
                </div>
            </div>

            <div
                class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
                data-max="50"
                data-min="1"
                data-label-max="Maximum:50"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="gap"
                    >Gap between images</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->gap}" id="m_gap" name="m_gap"/>
                </div>
            </div>

            <button type="submit" name="save_mobile_settings">Save</button>
        </div>
    </form>

    <form action="" method="POST">
        <div class="panel">
            <h3><i class="icon icon-cogs"></i> {l s='Refresh images' mod='instagram'}</h3>
            <button type="submit" name="refresh">Refresh</button>
        </div>
    </form>
</div>