<div id="djslider-block<?php echo $mid; ?>">
    <div
    id="djslider-loader<?php echo $mid; ?>" class="djslider-loader">
        <div style="opacity: 1; display: block; visibility: visible;"
        id="djslider<?php echo $mid; ?>" class="djslider">
            <div id="slider-container<?php echo $mid; ?>" class="slider-container">
                <ul style="position: relative; left: -4px;" id="slider<?php echo $mid; ?>">
                <?php foreach($slides as $slide):?>
                    <li style="position:absolute;">
                    	<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1)==2) { ?>
							<a <?php echo ($params->get('link_image',1)==2 ? 'class="modal"' : ''); ?> href="<?php echo ($params->get('link_image',1)==2 ? $slide->image : $slide->link); ?>" target="<?php echo $slide->target; ?>">
						<?php } ?>
							<img src="<?php echo $slide->image; ?>" alt="<?php echo $slide->alt; ?>" />
						<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1)==2) { ?>
							</a>
						<?php } ?>
                    </li>
                <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div style="clear: both"></div>