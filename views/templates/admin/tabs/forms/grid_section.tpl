<div
        class="form-group prestashop-number-input prestashop-number-input-enable-arrows"
        data-max="50"
        data-min="1"
        data-label-max="Maximum:50"
        data-label-min="Minimum:1"
        data-label-nan="Not a number."
>
    <label class="form-control-label" for="{$version}grid_row"
    >{l s='Number of rows' mod='instagram'}</label
    >
    <div class="prestashop-number-input-inputs">
        <input class="form-control" type="number" value="{$settings->grid_row}" id="{$version}grid_row"
               name="{$version}grid_row"/>
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
    <label class="form-control-label" for="{$version}grid_column"
    >{l s='Number of columns' mod='instagram'}</label
    >
    <div class="prestashop-number-input-inputs">
        <input class="form-control" type="number" value="{$settings->grid_column}" id="{$version}grid_column"
               name="{$version}grid_column"/>
    </div>
</div>