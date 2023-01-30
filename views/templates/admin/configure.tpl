{*
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $message_type == 'error'}
<div class="alert alert-danger" role="alert">
	<p class="alert-text">{$message}</p>
</div>
{/if}

{if $message_type == 'confirmation'}
	<div class="alert alert-success" role="alert">
		<p class="alert-text">{$message}</p>
	</div>
{/if}

<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Connect your account to an Instagram App' mod='instagram'}</h3>
	<p>
		{l s='This step will generate code used to generate Access Token to access your media. You need facebook account and you have to create app in developers.facebook.com. You can find details in documentation.' mod='instagram'} <br />
		<form action="https://api.instagram.com/oauth/authorize" method="GET" target="_blank">
			{l s='Copy url of your page that will be used to redirect you from an Instagram authorization page that you have set in a Valid OAuth Redirect URIs e.g. https://test.page.pl/ (it has to be https:// page)' mod='instagram'} <br />
			<input type="text" name="redirect_uri" placeholder="Redirect URL" value=""></input>
			{l s='Instagram App ID, you can find it in Instagram Basic Display of your app' mod='instagram'}
			<input type="text" name="client_id" value={$instagram_app_id}></input>
			<input type="hidden" name="scope" value="user_profile,user_media"></input>
			<input type="hidden" name="response_type" value="code"></input>
			<button type="submit" name="authorize" class="btn btn-default">Authorize</button>
		</form>
	</p>
</div>

<div class="panel">
	<h3><i class="icon icon-cogs"></i> {l s='Add Instagram Account' mod='instagram'}</h3>
	<p>
		{l s='This step will generate code used to generate Access Token to access your media.' mod='instagram'} <b>Code generated from authorization can be used only once. Authorize again if you didn't copy the code.</b> <br />
		<form action="" method="POST">
			{l s='Url from previous step' mod='instagram'} <br />
			<input type="text" name="redirect_uri" value="" placeholder="Redirect URL"></input>
			{l s='Instagram App ID' mod='instagram'} <br />
			<input type="text" name="instagram_app_id" value={$instagram_app_id}></input>
			{l s='Instagram App Secret' mod='instagram'}
			<input type="text" name="instagram_app_secret" value={$instagram_app_secret}></input>
			{l s='Code (copied from the url without #_)' mod='instagram'}
			<input type="text" name="instagram_code" value={$instagram_code}></input>
			<button type="submit" name="add_account" class="btn btn-default">Add</button>
		</form>
	</p>
</div>

{if !empty($username)}
<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Current account' mod='instagram'}</h3>
	<p>
		<form action="" method="POST">
			{l s=$username mod='instagram'}<br /><button type="submit" name="delete_account" class="btn btn-default">Delete</button>
		</form>
	</p>
</div>
{/if}