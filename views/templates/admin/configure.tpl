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
    <h3><i class="icon icon-cogs"></i> {l s='Add Instagram App Data' mod='instagram'}</h3>
    <p>
        {l s='You have to add valid ID and Secret of you Instagram App created in' mod='instagram'} <a
                href="https://developers.facebook.com/">Meta for Developers</a> <br/>
        {l s='Link to add for Valid OAtuth Redirect URIs:' mod='instagram'} <b>{$redirect_uri}</b>
    </p>
    <form action="" method="POST">
        <div class="form-wrapper">
            <div class="form-group">
                {l s='Instagram App ID' mod='instagram'} <br/>
                <input type="text" name="instagram_app_id" value={$instagram_app_id}/>
            </div>
            <div class="form-group">
                {l s='Instagram App Secret' mod='instagram'}
                <input type="text" name="instagram_app_secret" value={$instagram_app_secret}/>
            </div>
            <div class="panel-footer">
                <button type="submit" name="add_config" class="btn btn-default">Add</button>
            </div>
        </div>
    </form>
</div>

<div class="panel">
    <h3><i class="icon icon-cogs"></i> {l s='Authorize your account to an Instagram App' mod='instagram'}</h3>
    <p>
        {l s='Once you\'ve added basic configuration, press authorize to connect to your Instagram account and generate Access Token. After this step complete you will be able to access photos from your Instagram Account.' mod='instagram'}
        <br/>
    </p>
    <form action="https://api.instagram.com/oauth/authorize" method="GET" target="_blank">
        <input type="hidden" name="client_id" value={$instagram_app_id}/>
        <input type="hidden" name="redirect_uri" value={$redirect_uri}/>
        <input type="hidden" name="scope" value="user_profile,user_media"/>
        <input type="hidden" name="response_type" value="code"/>
        <div class="panel-footer">
            <button type="submit" name="authorize" class="btn btn-default">Authorize</button>
        </div>
    </form>
</div>

<div class="panel">
    <h3><i class="icon icon-tags"></i> {l s='Additional configuration' mod='instagram'}</h3>
    {l s='To make this module work correctly you have to add tokenrefresh and feedrefresh Cron jobs to you server. Check documentaion for additional information.' mod='instagram'}
    <br/>
</div>

{if !empty($username)}
    <div class="panel">
        <h3><i class="icon icon-tags"></i> {l s='Current account' mod='instagram'}</h3>
        <form action="" method="POST">
            {l s=$username mod='instagram'}<br/>
            <div class="panel-footer">
                <button type="submit" name="delete_account" class="btn btn-default">Delete</button>
            </div>
        </form>
    </div>
{/if}