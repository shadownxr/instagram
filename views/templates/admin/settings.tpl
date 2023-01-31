{if $is_connected == false}
<div class="alert alert-warning" role="alert">
	<p class="alert-text">Your account is not configured, go to module configuration to set your Instagram Account</p>
</div>
{else}
<div class="panel" style="max-height:500px;overflow:scroll;">
    <h3><i class="icon icon-cogs"></i> {l s='Preview' mod='instagram'}</h3>
    {if $settings->show_title == true}
        <div style="text-align: center;"><h1>{$settings->title}</h1></div>
    {/if}
    <div class="instagram_image_display" style="display:flex;flex-direction:{$settings->flex_direction};">
        {foreach from=$images_data item=data}
            <div class="image" style="display:flex;flex-direction:{$settings->description_alignment};">
                <img src={$data['image_url']} class="images" height="{$settings->image_height}" width="{$settings->image_width}" 
                    style="margin:{$settings->image_margin}px;
                    border-radius:{$settings->image_border_radius}px;
                "/>
                {if $settings->show_description == true}
                    {if array_key_exists('description', $data)}
                        <div><p>{$data['description']}</p></div>
                    {/if}
                {/if}
            </div>
        {/foreach}
    </div>
</div>

<form action="" method="POST">
    <div class="panel">
        <h3><i class="icon icon-cogs"></i> {l s='Image settings' mod='instagram'}</h3>
        
        <div class="form-group">
                
        <label class="form-control-label" for="image_width">Change display direction</label>
        <div class="form-check form-check-radio">
            <label class="form-check-label">
                <input
                class="form-check-input"
                type="radio"
                name="display_direction"
                id="display_direction_column"
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
                name="display_direction"
                id="display_direction_row"
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
            <label class="form-control-label" for="image_width"
                >Width of image</label
            >
            <div class="prestashop-number-input-inputs">
                <input class="form-control" type="number" value="{$settings->image_width}" id="image_width" name="image_width"/>
            </div>
        </div>

        <div
            class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
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
                <input class="form-control" type="number" value="{$settings->image_height}" id="image_height" name="image_height"/>
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
                <input class="form-control" type="number" value="{$settings->image_margin}" id="image_margin" name="image_margin"/>
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
                <input class="form-control" type="number" value="{$settings->image_border_radius}" id="image_border_radius" name="image_border_radius"/>
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
        
        <label class="form-control-label"
                >Description alignment: </label
            >
        <div class="form-select">
            <select class="form-control custom-select" name="description_alignment">
                <option value="column" {if $settings->description_alignment == 'column'}selected{/if}>Bottom</option>
                <option value="row-reverse" {if $settings->description_alignment == 'row-reverse'}selected{/if}>Left</option>
                <option value="column-reverse" {if $settings->description_alignment == 'column-reverse'}selected{/if}>Top</option>
                <option value="row" {if $settings->description_alignment == 'row'}selected{/if}>Right</option>
            </select>
        </div>

        </div>
        <button type="submit" name="save_settings">Save</button>
    </div>

    <div class="panel">
        <h3><i class="icon icon-cogs"></i> {l s='Gallery settings' mod='instagram'}</h3>

        <label class="form-control-label">Select hook to display images: </label>
        <div class="form-select">
            <select class="form-control custom-select" name="display_hook">
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
                <input class="form-control" type="number" value="{$settings->max_images_fetched}" id="max_images_fetched" name="max_images_fetched"/>
            </div>
        </div>

        <div
            class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
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
                <input class="form-control" type="number" value="{$settings->max_images_visible}" id="max_images_visible" name="max_images_shown"/>
            </div>
        </div>
        <button type="submit" name="save_settings">Save</button>
    </div>
</form>

<form action="" method="POST">
    <div class="panel">
        <h3><i class="icon icon-cogs"></i> {l s='Refresh images' mod='instagram'}</h3>
        <button type="submit" name="refresh">Refresh</button>
    </div>
</form>
{/if}