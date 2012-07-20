<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');
if(!JComponentHelper::isEnabled('com_djimageslider', true)){
    $app->enqueueMessage(JText::_('MOD_DJIMAGESLIDER_NO_COMPONENT'),'notice');
    return;
}

require_once (JPATH_ROOT.DS.'modules'.DS.'mod_djimageslider'.DS.'helper.php');

JHTML::_('behavior.mootools');
JHTML::_('behavior.mootools', true);
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addScript('modules/mod_djimageslider_simple/assets/SlideShow.js');

// taking the slides from the source
if($params->get('slider_source')==1) {
	$slides = modDJImageSliderHelper::getImagesFromDJImageSlider($params);
	if($slides==null) {
		$app->enqueueMessage(JText::_('MOD_DJIMAGESLIDER_NO_CATEGORY_OR_ITEMS'),'notice');
		return;
	}
} else {
	$slides = modDJImageSliderHelper::getImagesFromFolder($params);
	if($slides==null) {
		$app->enqueueMessage(JText::_('MOD_DJIMAGESLIDER_NO_CATALOG_OR_FILES'),'notice');
		return;
	}
}

$mid = $module->id;
$mid = 97;
$js = sprintf('
window.addEvent("domready", function(){    
	BannerSlideShow = new SlideShow("slider%d", {
		autoplay: true,
		delay: 4000,
		transition: "fade"
	});
});', $mid);
$document->addScriptDeclaration($js);

require(JModuleHelper::getLayoutPath('mod_djimageslider_simple'));