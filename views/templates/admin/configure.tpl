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

<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Connect your account with an Instagram App' mod='instagram'}</h3>
	<p>
		{l s='This step will generate code used to generate Access Token to access your media.' mod='instagram'} <br />
		<form action="https://api.instagram.com/oauth/authorize" method="GET" target="_blank">
			{l s='Copy current page url that will be used to redirect you from an Instagram authorization page. (end with /)' mod='instagram'} <br />
			<input type="text" name="redirect_uri" placeholder="Redirect URL" value="https://www.google.com/"></input>
			{l s='Instagram APP ID: default value points to default App' mod='instagram'}
			<input type="text" name="client_id" value="1788770188184590"></input>
			<input type="hidden" name="scope" value="user_profile,user_media"></input>
			<input type="hidden" name="response_type" value="code"></input>
			<button type="submit">Authorize</button>
		</form>
	</p>
</div>

<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Add Instagram Account' mod='instagram'}</h3>
	<p>
		{l s='This step will generate code used to generate Access Token to access your media.' mod='instagram'} <br />
		<form action="" method="GET" target="_blank">
			{l s='Instagram App ID' mod='instagram'} <br />
			<input type="text" name="redirect_uri" placeholder="Redirect URL" value=""></input>
			{l s='Instagram APP Secret' mod='instagram'}
			<input type="text" name="client_id" value=""></input>
			{l s='Code' mod='instagram'}
			<input type="text" name="response_type" value=""></input>
			<button type="submit">Add</button>
		</form>
	</p>
</div>

{if !empty($username)}
<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Current account' mod='instagram'}</h3>
	<p>
		{l s=$username mod='instagram'} <br />
	</p>
</div>
{/if}