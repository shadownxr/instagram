{if $is_connected == false}
<div class="alert alert-warning" role="alert">
	<p class="alert-text">Your account is not configured, go to module configuration to set your Instagram Account</p>
</div>
{else}
<div class="switch_tabs">
    <button name="desktop_switch">Desktop</button>
    <button name="mobile_switch">Mobile</button>
</div>

{include file='./tabs/settings_desktop.tpl' settings=$settings images_data=$images_data}
{include file='./tabs/settings_mobile.tpl' settings=$m_settings images_data=$images_data}

{/if}