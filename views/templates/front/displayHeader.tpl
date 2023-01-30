<div class="display_header">
    <div><p>{$display_style->flex_direction}</p></div>
    {if !empty($images_data)}
        {if $display_style->show_title == true}
            <div style="text-align: center;"><h1>{$display_style->title}</h1></div>
        {/if}
        <div class="instagram_image_display" style="flex-direction:{$display_style->flex_direction};">
            {foreach from=$images_data item=data}
                <div style="display:flex;flex-direction:{$display_style->description_alignment};">
                    <img src={$data['image_url']} height="{$display_style->image_height}" width="{$display_style->image_width}" 
                        style="margin:{$display_style->image_margin}px;
                        border-radius:{$display_style->image_border_radius}px;
                    "/>
                    {if $display_style->show_description == true}
                        {if array_key_exists('description', $data)}
                            <div><p>{$data['description']}</p></div>
                        {/if}
                    {/if}
                </div>
            {/foreach}
        </div>
    {/if}
</div>