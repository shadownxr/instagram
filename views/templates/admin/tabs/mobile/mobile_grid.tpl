<div class="panel" style="max-height:500px;overflow:scroll;">
    <div class="panel-heading">
        <i class="icon icon-cogs"></i>
        {l s='Mobile Preview' mod='instagram'}
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
                        <div class="m_grid_display"
                             style="grid-template-columns: repeat({$settings->grid_column}, 1fr); grid-template-rows: repeat({$settings->grid_row}, 1fr); grid-gap: {$settings->gap}px;">
                            {foreach from=$images_data item=data}
                                <div class="item">
                                    <img src={$data['image_url']} height="{$settings->image_size}"
                                         width="{$settings->image_size}" class="mobile_preview_images">
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>

<form action="" method="POST" class="defaultForm form-horizontal">
    <div class="panel">
        <h3><i class="icon icon-cogs"></i> {l s='Image settings' mod='instagram'}</h3>

        <div class="form-wrapper">
            <div class="form-group">

                <label class="form-control-label" for="m_display_style">Change display style</label>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="m_display_style"
                                id="m_display_style_slider"
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
                                name="m_display_style"
                                id="m_display_style_grid"
                                value="grid"
                                {if $settings->display_style  == "grid"}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round">Grid</i>
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
                    <label class="form-control-label" for="m_image_size"
                    >Size of image</label
                    >
                    <div class="prestashop-number-input-inputs">
                        <input class="form-control" type="number" value="{$settings->image_size}" id="m_image_size"
                               name="m_image_size"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="save_mobile_settings" class="btn btn-default pull-right">Save</button>
        </div>
    </div>

    <div class="panel">
        <h3><i class="icon icon-cogs"></i> {l s='Gallery settings' mod='instagram'}</h3>

        <div class="form-wrapper">
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
                    <input class="form-control" type="number" value="{$settings->max_images_fetched}"
                           id="m_max_images_fetched" name="m_max_images_fetched"/>
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
                <label class="form-control-label" for="m_images_per_gallery"
                >Number of images display at one time</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->images_per_gallery}"
                           id="m_images_per_gallery" name="m_images_per_gallery"/>
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
                <label class="form-control-label" for="m_gap"
                >Gap between images</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->gap}" id="m_gap" name="m_gap"/>
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
                <label class="form-control-label" for="m_grid_row"
                >Number of rows</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->grid_row}" id="m_grid_row"
                           name="m_grid_row"/>
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
                <label class="form-control-label" for="m_grid_column"
                >Number of columns</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->grid_column}" id="m_grid_column"
                           name="m_grid_column"/>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="save_mobile_settings" class="btn btn-default pull-right">Save</button>
        </div>
    </div>
</form>
