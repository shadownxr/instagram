{if !empty($images_data)}
    <div class="instagram_display">
        <div class="section">
            {if $settings->show_title == true}
                <div class="section__header">
                    <div class="section__header--title">
                        {$settings->title nofilter}
                    </div>
                    <div class="section__header--link">
                        <a href="https://instagram.com/{$username}/" class="btn btn-default btn-primary">{l s='Follow Us @' mod='arkoninstagram'}{$username}</a>
                    </div>
                </div>
            {/if}
            {if $settings->display_style == 'slider'}
                {include file='./display/slider.tpl' images_data=$images_data settings=$settings version=$version}
            {elseif $settings->display_style == 'grid'}
                {include file='./display/grid.tpl' images_data=$images_data settings=$settings version=$version}
            {/if}
        </div>
    </div>
{/if}
