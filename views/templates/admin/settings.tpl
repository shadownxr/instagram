{if $is_connected == false}
    <div class="alert alert-warning" role="alert">
	<p class="alert-text">Your account is not configured, go to module configuration to set your Instagram Account</p>
</div>
{else}
    <form action="" method="POST">
        <div class="panel">
            <h3><i class="icon icon-tags"></i> {l s='Change display type' mod='instagram'}</h3>
            
            <div class="form-group">
            
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="display_direction" id="display_direction_column" value="column">
                <label for="display_direction_column">Column</label>
                <input type="radio" name="display_direction" id="display_direction_row" value="row" checked="checked">
                <label for="display_direction_row">Row</label>
                <a class="slide-button btn"></a>
            </span>
                            
            <p class="help-block">
                Change display direction
            </p>
            </div>
            <button type="submit" name="save_settings">Save</button>
        </div>
    </form>
{/if}