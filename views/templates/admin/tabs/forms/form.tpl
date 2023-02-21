<form action="" method="POST" class="defaultForm form-horizontal">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon icon-cogs"></i>
            {l s='Display settings' mod='instagram'}
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <label class="form-control-label"
                       for="{$version}display_style">{l s='Change display style' mod='instagram'}</label>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="{$version}display_style"
                                id="{$version}display_style_slider"
                                value="slider"
                                {if $settings->display_style == "slider"}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round">{l s='Slider' mod='instagram'}</i>
                    </label>
                </div>


                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="{$version}display_style"
                                id="{$version}display_style_grid"
                                value="grid"
                                {if $settings->display_style  == "grid"}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round">Grid</i>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label"
                >{l s='Show title' mod='instagram'} </label
                >
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="{$version}show_title"
                                id="{$version}show_title_true"
                                value="1"
                                {if $settings->show_title == true}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        {l s='Yes' mod='instagram'}
                    </label>
                </div>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="{$version}show_title"
                                id="{$version}show_title_false"
                                value="0"
                                {if $settings->show_title == false}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        {l s='No' mod='instagram'}
                    </label>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="save_desktop_settings"
                    class="btn btn-default pull-right">{l s='Save' mod='instagram'}</button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <i class="icon icon-cogs"></i>
            {l s='Gallery settings' mod='instagram'}
        </div>

        <div class="form-wrapper">
            <div
                    class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
                    data-max="1920"
                    data-min="1"
                    data-label-max="Maximum:1920"
                    data-label-min="Minimum:1"
                    data-label-nan="Not a number."
            >
                <label class="form-control-label" for="{$version}image_size"
                >{l s='Size of image'}</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->image_size}" id="{$version}image_size"
                           name="{$version}image_size"/>
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label">{l s='Select hook to display images:' mod='instagram'}</label>
                <div class="form-select">
                    <select class="form-control custom-select" name="{$version}display_hook">
                        {foreach from=$display_hooks item=display_hook}
                            <option value="{$display_hook['name']}" {if $settings->hook == $display_hook['name']} selected {/if}>{$display_hook['name']}</option>
                        {/foreach}
                    </select>
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
                <label class="form-control-label" for="{$version}max_images_fetched"
                >{l s='Maximum number of images fetched from your Instagram gallery' mod='instagram'}</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->max_images_fetched}"
                           id="{$version}max_images_fetched" name="{$version}max_images_fetched"/>
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
                <label class="form-control-label" for="{$version}images_per_gallery"
                >{l s='Number of images display at one time' mod='instagram'}</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->images_per_gallery}"
                           id="{$version}images_per_gallery" name="{$version}images_per_gallery"/>
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
                <label class="form-control-label" for="{$version}gap"
                >{l s='Gap between images' mod='instagram'}</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->gap}" id="{$version}gap"
                           name="{$version}gap"/>
                </div>
            </div>

            {if $settings->display_style  == "grid"}
                {include file='./grid_section.tpl' settings=$settings version=$version}
            {/if}
        </div>
        <div class="panel-footer">
            <button type="submit" {if $version == $DESKTOP} name="save_desktop_settings" {else} name="save_mobile_settings" {/if}
                    class="btn btn-default pull-right">{l s='Save' mod='instagram'}</button>
        </div>
    </div>
</form>