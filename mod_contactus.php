<?php
/**
 * Contact Us entry point
 * 
 * @package    galaxysoft.php.joomla.modules
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * mod_contactus is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
 
// No direct access
defined('_JEXEC') or die;

// Usual variables
$app 		= JFactory::getApplication();
$tmpl 		= $app->getTemplate();
$doc 		= JFactory::getDocument(); 

$base 		= JURI::base();
$modBase 	= $base.'modules/mod_contactus';

// Parameters
$usePopup 			= $params->get("usePopup",   "1") == "1";
$useCaptcha 		= $params->get("useCaptcha", "1") == "1";


// Titles for buttons and captions for fields
$btnPopupTitle 		= $params->get("btnPopupTitle",		"Ask a question");
$btnSubmitTitle 	= $params->get("btnSubmitTitle",	"Send");
$popupTitle 		= $params->get("popupTitle",		"Ask a question");

$fldNameTitle 		= $params->get("fldNameTitle", 		"Your name");
$fldEmailTitle 		= $params->get("fldEmailTitle",		"Your Email");
$fldMessageTitle 	= $params->get("fldMessageTitle",	"Write your question here");
$fldCaptchaTitle 	= $params->get("fldCaptchaTitle",	"Captcha");

// Captcha configuration.
$captchaServerKey	= $params->get("captchaServerKey");
$captchaFrontKey	= $params->get("captchaFrontKey");
$captchaUseClientIp = $params->get("captchaUseClientIp", "1") == "1";

// Email parameters.
$mailSubject 		= $params->get("mailSubject",   	"Contactus from site"); // TODO add sitename to subject.
$mailRecipient 		= $params->get("mailRecipient", 	"");


// Styles for different visual elements.
$btnSubmitStyle 	= array(
	"backgroundColor"	=> $params->get('btnSubmitStyle_backgroundColor'	,'#628D2C'),
	"bottomBackgroundColor" => $params->get('btnSubmitStyle_bottomBackgroundColor'	,'#30693A'),
	"textColor"			=> $params->get('btnSubmitStyle_textColor'			,'#FFF'),
	"textShadow"		=> $params->get('btnSubmitStyle_textShadow'			,'#30693A'),
);

$inpStyle 			= array(
	"backgroundColor" 	=> $params->get("inpStyle_backgroundColor"	,"#ffffff"),
	"borderColor"     	=> $params->get("inpStyle_borderColor"		,"#cccccc"),
	"textColor"			=> $params->get("inpStyle_textColor"		,"#333333")
);

$fldTitleStyle		= array(
	"textColor"			=> $params->get("fldTitleStyle_textColor",	"#333333")
);

require_once dirname(__FILE__) . '/helper.php';

require JModuleHelper::getLayoutPath('mod_contactus');