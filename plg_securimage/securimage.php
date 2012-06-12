<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Captcha
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.environment.browser');
require(dirname(__FILE__) . '/securimage_class.php');

/**
 * Recaptcha Plugin.
 * Based on the oficial recaptcha library( http://recaptcha.net/plugins/php/ )
 *
 * @package     Joomla.Plugin
 * @subpackage  Captcha
 * @since       2.5
 */
class plgCaptchaSecurimage extends JPlugin
{
	public function __construct($subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Initialise the captcha
	 *
	 * @param	string	$id	The id of the field.
	 *
	 * @return	Boolean	True on success, false otherwise
	 *
	 * @since  2.5
	 */
	public function onInit($id)
	{
	}

	/**
	 * Gets the challenge HTML
	 *
	 * @return  string  The HTML to be embedded in the form.
	 *
	 * @since  2.5
	 */
	public function onDisplay($name, $id, $class)
	{
      $url = JURI::base(true).'/plugins/captcha/securimage/securimage_show.php';
      return sprintf('<img %s id="%s_image" src="%s" alt="CAPTCHA Image" />
<input type="text" id="%s" name="%s" size="10" maxlength="6" />
<a id="%s_link" href="#" onclick="document.getElementById(\'%s_image\').src = \'%s?\' + Math.random(); return false">[ Different Image ]</a>',$class ,$id, $url, $id, $name, $id, $id, $url);
	}

	/**
	  * Calls an HTTP POST function to verify if the user's guess was correct
	  *
	  * @return  True if the answer is correct, false otherwise
	  *
	  * @since  2.5
	  */
	public function onCheckAnswer($code)
    {
      $securimage = new JSecurimage();
      if(!$securimage->check($code)) {
          $this->_subject->setError(JText::_('Captcha is not valid.'));
          return false;
      } else {
          return true;
      }
	}
}
