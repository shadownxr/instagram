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
                        <section class="splide" id="preview_desktop_slider" aria-label="Splide Basic HTML Example">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    {foreach from=$images_data item=data}
                                        <li class="splide__slide">
                                            <img src={$data['image_url']} height="{$settings->image_size}"
                                                 width="{$settings->image_size}" class="desktop_preview_images"
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
</div>

<form action="" method="POST" class="defaultForm form-horizontal">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon icon-cogs"></i>
            {l s='Image settings' mod='instagram'}
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <label class="form-control-label" for="display_direction">Change display direction</label>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="display_style"
                                id="display_style_slider"
                                value="slider"
                                {if $settings->display_style == "slider"}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round">Slider</i>
                    </label>
                </div>

                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="display_style"
                                id="display_style_grid"
                                value="grid"
                                {if $settings->display_style  == "grid"}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round">Grid</i>
                    </label>
                </div>
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
                    <input class="form-control" type="number" value="{$settings->image_size}" id="image_size"
                           name="image_size"/>
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
                    <input class="form-control" type="number" value="{$settings->image_margin}" id="image_margin"
                           name="image_margin"/>
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
                    <input class="form-control" type="number" value="{$settings->image_border_radius}"
                           id="image_border_radius" name="image_border_radius"/>
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label"
                >Show description </label
                >
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="show_description"
                                id="show_description_true"
                                value="1"
                                {if $settings->show_description == true}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        Yes
                    </label>
                </div>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="show_description"
                                id="show_description_false"
                                value="0"
                                {if $settings->show_description == false}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        No
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label"
                >Description alignment </label
                >
                <div class="form-select">
                    <select class="form-control custom-select" name="description_alignment">
                        <option value="column" {if $settings->description_alignment == 'column'}selected{/if}>Bottom
                        </option>
                        <option value="row-reverse" {if $settings->description_alignment == 'row-reverse'}selected{/if}>
                            Left
                        </option>
                        <option value="column-reverse"
                                {if $settings->description_alignment == 'column-reverse'}selected{/if}>Top
                        </option>
                        <option value="row" {if $settings->description_alignment == 'row'}selected{/if}>Right</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="panel-footer">
            <button type="submit" name="save_desktop_settings" class="btn btn-default pull-right">Save</button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <i class="icon icon-cogs"></i>
            {l s='Gallery settings' mod='instagram'}
        </div>

        <div class="form-wrapper">

            <div class="form-group">
                <label class="form-control-label">Select hook to display images: </label>
                <div class="form-select">
                    <select class="form-control custom-select" name="display_hook">
                        {foreach from=$display_hooks item=display_hook}
                            <option value="{$display_hook['name']}" {if $settings->hook == $display_hook['name']} selected {/if}>{$display_hook['name']}</option>
                        {/foreach}
                    </select>
                </div>

            </div>
            <div class="form-group">
                <label class="form-control-label"
                >Show title </label
                >
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="show_title"
                                id="show_title_true"
                                value="1"
                                {if $settings->show_title == true}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        Yes
                    </label>
                </div>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="show_title"
                                id="show_title_false"
                                value="0"
                                {if $settings->show_title == false}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        No
                    </label>
                </div>
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
                    <input class="form-control" type="number" value="{$settings->max_images_fetched}"
                           id="max_images_fetched" name="max_images_fetched"/>
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
                    <input class="form-control" type="number" value="{$settings->images_per_gallery}"
                           id="images_per_gallery" name="images_per_gallery"/>
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
                    <input class="form-control" type="number" value="{$settings->gap}" id="gap" name="gap"/>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="save_desktop_settings" class="btn btn-default pull-right">Save</button>
        </div>
    </div>
</form>