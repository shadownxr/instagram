{assign var=DESKTOP value='desktop'}
{assign var=MOBILE value='mobile'}

{if $is_connected == false}
<div class="alert alert-warning" role="alert">
	<p class="alert-text">{l s='Your account is not configured, go to module configuration to set your Instagram Account' mod='instagram'}</p>
</div>
{else}

<div class="switch_tabs">
    <button name="desktop_switch" class="btn btn-primary" style="border-bottom: none; border-radius: unset;">Desktop</button>
    <button name="mobile_switch" class="btn btn-secondary" style="border-bottom: none; border-radius: unset;">Mobile</button>
</div>

{include file='./tabs/display.tpl' settings=$settings images_data=$images_data version=$DESKTOP active=true}
{include file='./tabs/display.tpl' settings=$m_settings images_data=$images_data version=$MOBILE active=false}

{/if}