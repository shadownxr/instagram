<form action="" method="POST" class="defaultForm form-horizontal">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon icon-cogs"></i>
            {l s='Gallery settings' mod='instagram'}
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <label class="form-control-label">{l s='Select hook which will display gallery (desktop and mobile share this hook):' mod='instagram'}</label>
                <div class="form-select">
                    <select class="form-control custom-select" name="{$version}_display_hook">
                        {foreach from=$display_hooks item=display_hook}
                            <option value="{$display_hook['name']}" {if $settings->hook == $display_hook['name']} selected {/if}>{$display_hook['name']}</option>
                        {/foreach}
                        <option value="instagramDisplay" {if $settings->hook == "instagramDisplay"} selected {/if}>{l s='instagramDisplay'}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label"
                       for="{$version}_display_style">{l s='Change display style' mod='instagram'}</label>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="{$version}_display_style"
                                id="{$version}_display_style_slider"
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
                                name="{$version}_display_style"
                                id="{$version}_display_style_grid"
                                value="grid"
                                {if $settings->display_style  == "grid"}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round">{l s='Grid' mod='arkoninstagram'}</i>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label"
                >{l s='Show title' mod='arkoninstagram'} </label
                >
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="{$version}_show_title"
                                id="{$version}_show_title_true"
                                value="1"
                                {if $settings->show_title == true}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        {l s='Yes' mod='arkoninstagram'}
                    </label>
                </div>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input
                                class="form-check-input"
                                type="radio"
                                name="{$version}_show_title"
                                id="{$version}_show_title_false"
                                value="0"
                                {if $settings->show_title == false}
                                    checked
                                {/if}
                        />
                        <i class="form-check-round"></i>
                        {l s='No' mod='arkoninstagram'}
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
                    <label class="form-control-label" for="{$version}_max_images_fetched"
                    >{l s='Maximum number of images fetched from your Instagram gallery' mod='instagram'}</label
                    >
                    <div class="prestashop-number-input-inputs">
                        <input class="form-control" type="number" value="{$settings->max_images_fetched}"
                               id="{$version}_max_images_fetched" name="{$version}_max_images_fetched"/>
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
                    <label class="form-control-label" for="{$version}_images_per_gallery"
                    >{l s='Number of images display at one time' mod='arkoninstagram'}</label
                    >
                    <div class="prestashop-number-input-inputs">
                        <input class="form-control" type="number" value="{$settings->images_per_gallery}"
                               id="{$version}_images_per_gallery" name="{$version}_images_per_gallery"/>
                    </div>
                </div>
            </div>
            <div id="settings_notification"></div>
        </div>

        <div class="panel-footer">
            <button type="submit" name="save_{$version}_settings"
                    class="btn btn-default pull-right">{l s='Save' mod='arkoninstagram'}</button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <i class="icon icon-cogs"></i>
            {l s='Image settings' mod='arkoninstagram'}
        </div>

        <div class="form-wrapper">
            <div class="form-group {if $settings->show_title === false}hide{/if}">
                <label class="form-control-label" for="{$version}_title"
                >{l s='Gallery title' mod='arkoninstagram'}</label
                >
                <div class="prestashop-number-input-inputs">
                    <textarea class="form-control autoload_rte rte" type="text" value="{$settings->title}" id="{$version}_title"
                           name="{$version}_title">{$settings->title}</textarea>
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
                <label class="form-control-label" for="{$version}_image_size"
                >{l s='Size of image' mod='arkoninstagram'}</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->image_size}" id="{$version}_image_size"
                           name="{$version}_image_size"/>
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
                <label class="form-control-label" for="{$version}_gap"
                >{l s='Gap between images' mod='arkoninstagram'}</label
                >
                <div class="prestashop-number-input-inputs">
                    <input class="form-control" type="number" value="{$settings->gap}" id="{$version}_gap"
                           name="{$version}_gap"/>
                </div>
            </div>

            {if $settings->display_style  == "grid"}
                {include file='./grid_section.tpl' settings=$settings version=$version}
            {/if}
        </div>
        <div class="panel-footer">
            <button type="submit" name="save_{$version}_settings"
                    class="btn btn-default pull-right">{l s='Save' mod='arkoninstagram'}</button>
        </div>
    </div>
</form>

<script type="text/javascript">
  var iso = '{$iso|addslashes}';
  var pathCSS = '{$smarty.const._THEME_CSS_DIR_|addslashes}';
  {*var ad = '{$ad|addslashes}';*}

  $(document).ready(function(){
      {block name="autoload_tinyMCE"}
    tinySetup({
      editor_selector :"autoload_rte"
    });
      {/block}
  });
</script>