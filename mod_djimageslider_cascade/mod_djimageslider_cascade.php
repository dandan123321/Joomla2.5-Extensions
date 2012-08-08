<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');
if(!JComponentHelper::isEnabled('com_djimageslider', true)){
    $app->enqueueMessage(JText::_('MOD_DJIMAGESLIDER_NO_COMPONENT'),'notice');
    return;
}

require_once (JPATH_ROOT.DS.'modules'.DS.'mod_djimageslider'.DS.'helper.php');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djimageslider'.DS.'lib'.DS.'image.php');

JHTML::_('behavior.mootools');
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addScript(JURI::root().'modules/mod_djimageslider_cascade/assets/slideshow.js');
$document->addStyleSheet(JURI::root().'modules/mod_djimageslider_cascade/assets/style.css');

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

foreach($slides as $key => $slide) {
    $slides[$key]->image = DJImageResizer::createThumbnail($slide->image, 'cache/com_djimageslider', 799, 402);
    $slides[$key]->description = strip_tags($slide->description);
    if(JString::strlen($slides[$key]->description) > 100) {
        $slides[$key]->description = JString::substr($slides[$key]->description, 0, 100).'...';
    }
}

$mid = $module->id;
$js = sprintf('
window.addEvent("domready", function(){    
    BannerSlideShow = new MooSlideshow(document.id("djslider%d"), {
        prevButton: document.getElement(".btn-previous"),
        nextButton: document.getElement(".btn-next")
    });
});', $mid);
$document->addScriptDeclaration($js);

require(JModuleHelper::getLayoutPath('mod_djimageslider_cascade'));
