<div>
    {if !empty($images_url)}
        {foreach from=$images_url item=url}
        <img src={$url['media_url']} />
        {/foreach}
    {/if}
</div>