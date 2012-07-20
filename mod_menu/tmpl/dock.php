<?php
// No direct access.
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$menuPath = '/modules/mod_menu/assets/';
$loadJQuery = true;
if($loadJQuery) {
    $doc->addScript('http://ajax.microsoft.com/ajax/jquery/jquery-1.7.2.min.js');
}
$doc->addScript(JURI::root().$menuPath.'interface.js');
$doc->addStylesheet(JURI::root().$menuPath.'dock.css');
?>

<div class="menu<?php echo $class_sfx;?> dock"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
    } else {
        echo 'id="dockMenu"';
    }
?>>
<div class="dock-container">
<?php
foreach ($list as $i => &$item) :
	$class = 'item-'.$item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}

	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent) {
		$class .= ' parent';
	}

	if (!empty($class)) {
		$class = ' class="'.trim($class) .'"';
	}

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_menu', 'dock_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'dock_url');
			break;
	endswitch;
endforeach;
?></div>
</div>
<script type="text/javascript">
(function($){
    $('#<?php echo $params->get('tag_id')?$params->get('tag_id'):'dockMenu'; ?>').Fisheye(
        	{
				maxWidth: 20,
				items: 'a',
				itemsText: 'span',
				container: '.dock-container',
				itemWidth: 40,
				proximity: 60,
				alignment : 'left',
				valign: 'bottom',
				space: 20,
				halign : 'center'
        	}
        );
})(jQuery);
</script>
