{if $is_connected == false}
<div class="alert alert-warning" role="alert">
	<p class="alert-text">Your account is not configured, go to module configuration to set your Instagram Account</p>
</div>
{else}
    <form action="" method="POST">
        <div class="panel">
            <h3><i class="icon icon-cogs"></i> {l s='Image settings' mod='instagram'}</h3>
            
            <div class="form-group">
            
            <span class="switch prestashop-switch fixed-width-lg" data-item="">
                <input type="radio" name="display_direction" id="display_direction_column" value="column">
                <label for="display_direction_column">Column</label>
                <input type="radio" name="display_direction" id="display_direction_row" value="row" checked="checked">
                <label for="display_direction_row">Row</label>
                <a class="slide-button btn"></a>
            </span>
                            
            <p class="help-block">
                Change display direction
            </p>

            <div
                class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
                data-max="1920"
                data-min="1"
                data-label-max="Maximum:1920"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="image_width"
                    >Width of image</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$set_values->image_width}" id="image_width" name="image_width"/>
                </div>
            </div>

            <div
                class="form-group prestasho-number-input prestasho-number-input-enable-arrows"
                data-max="1080"
                data-min="1"
                data-label-max="Maximum:1080"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="image_height"
                    >Height of image</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$set_values->image_height}" id="image_height" name="image_height"/>
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
                <label class="form-control-label" for="image_margin"
                    >Margin of image</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$set_values->image_margin}" id="image_margin" name="image_margin"/>
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
                    <input class="form-control" type="number" value="{$set_values->image_border_radius}" id="image_border_radius" name="image_border_radius"/>
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
                    name="show_description"
                    id="show_description_true"
                    value="1"
                    />
                    <i class="form-check-round"></i>
                    True
                </label>
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="show_description"
                    id="show_description_false"
                    value="0"
                    checked
                    />
                    <i class="form-check-round"></i>
                    False
                </label>
            </div>
            
            <label class="form-control-label"
                    >Description alignment: </label
                >
            <div class="form-select">
                <select class="form-control custom-select" name="description_alignment">
                    <option selected value="column">Bottom</option>
                    <option value="row-reverse">Left</option>
                    <option value="column-reverse">Top</option>
                    <option value="row">Right</option>
                </select>
            </div>

            </div>
            <button type="submit" name="save_settings">Save</button>
        </div>

        <div class="panel">
            <h3><i class="icon icon-cogs"></i> {l s='Gallery settings' mod='instagram'}</h3>
            <label class="form-control-label"
                    >Show title: </label
                >
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="show_title"
                    id="show_title_true"
                    value="1"
                    />
                    <i class="form-check-round"></i>
                    True
                </label>
                <label class="form-check-label">
                    <input
                    class="form-check-input"
                    type="radio"
                    name="show_title"
                    id="show_title_false"
                    value="0"
                    checked
                    />
                    <i class="form-check-round"></i>
                    False
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
                    <input class="form-control" type="number" value="{$set_values->max_images_fetched}" id="max_images_fetched" name="max_images_fetched"/>
                </div>
            </div>

            <div
                class="form-group prestasho-number-input prestasho-number-input-enable-arrows"
                data-max="1080"
                data-min="1"
                data-label-max="Maximum:1080"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="max_images_visible"
                    >Maximum number of images visible in gallery</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$set_values->max_images_visible}" id="max_images_visible" name="max_images_shown"/>
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
                <label class="form-control-label" for="gallery_width"
                    >Width of gallery</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="1" id="gallery_width" name="gallery_width"/>
                </div>
            </div>

            <div
                class="form-group prestasho-number-input prestasho-number-input-enable-arrows"
                data-max="1080"
                data-min="1"
                data-label-max="Maximum:1080"
                data-label-min="Minimum:1"
                data-label-nan="Not a number."
                >
                <label class="form-control-label" for="gallery_height"
                    >Height of gallery</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="1" id="gallery_height" name="gallery_height"/>
                </div>
            </div>
            <button type="submit" name="save_settings">Save</button>
    </form>
{/if}