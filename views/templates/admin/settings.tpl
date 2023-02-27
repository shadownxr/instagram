{if $is_connected == false}
<div class="alert alert-warning" role="alert">
	<p class="alert-text">Your account is not configured, go to module configuration to set your Instagram Account</p>
</div>
{else}

<div class="switch_tabs">
    <button name="desktop_switch" class="btn btn-primary" style="border-bottom: none; border-radius: unset;">Desktop</button>
    <button name="mobile_switch" class="btn btn-secondary" style="border-bottom: none; border-radius: unset;">Mobile</button>
</div>

{assign var=DESKTOP value=''}
{assign var=MOBILE value='m_'}

{include file='./tabs/settings_desktop.tpl' settings=$settings images_data=$images_data}
{include file='./tabs/settings_mobile.tpl' settings=$m_settings images_data=$images_data}

{/if}