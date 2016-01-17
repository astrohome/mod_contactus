<?php
/**
 * Helper class for Contactus module
 * 
 * @package    galaxysoft.php.joomla.modules
 * @subpackage Modules
 * @license        GNU/GPL, see LICENSE.php
 * mod_contactus is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModContactusHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function validateAndSendAjax()
    {
    	$input   = JFactory::getApplication()->input;

    	$name    = $input->getString('name');
    	$email   = $input->getString('email');
    	$message = $input->getString('message');
    	$captcha = $input->getString('captcha');

    	$valid = false;

    	if (ModContactusHelper::isCaptchaEnabled()) {
    		if (!isset($captcha)) return false;

    		$valid = ModContactusHelper::validateCaptcha($captcha);
    	} else {
    		$valid = true;
    	}

    	if (!$valid) return false;

    	if (isset($email) && isset($message) && isset($name)) {
    		$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$sender = array( 
			    $email,
			    $name 
			);
			$mailer->setSender($sender);

			$mailer->setSubject(ModContactusHelper::getMailSubject());
			$mailer->addRecipient(ModContactusHelper::getMailRecepient());
		
			$body = "Тема: ".ModContactusHelper::getMailSubject()."<br/>";
			$body.= "Имя: ".$name."<br/>";
			$body.= "Email: ".htmlspecialchars($email)."<br/><br/>";
			$body.= htmlspecialchars($message)."<br/>";
		
			$mailer->setBody($body);
			$mailer->isHTML(true);
			//$mailer->Encoding = 'base64';
			$send = $mailer->Send();
			if ( $send !== true ) {
			    echo 'Error sending email: ' . $send->__toString();
			} else {
			    return true;
			}
    	}

    	return false;
    }

    private static function getModuleParams() {
    	jimport( 'joomla.html.parameter' );
		jimport( 'joomla.application.module.helper' );
		$module = JModuleHelper::getModule('mod_contactus');
		$moduleParams = new JRegistry();
		return $moduleParams->loadString($module->params);
    }

    private static function isCaptchaEnabled() {
    	$moduleParams = ModContactusHelper::getModuleParams();
		return $moduleParams['useCaptcha'] == '1';
    }

    private static function isCaptchaUseClientIp() {
    	$moduleParams = ModContactusHelper::getModuleParams();
		return $moduleParams['captchaUseClientIp'] == '1';
    }

    private static function getCaptchaServerKey() {
    	$moduleParams = ModContactusHelper::getModuleParams();
		return $moduleParams['captchaServerKey'];
    }

    private static function getMailRecepient() {
    	$moduleParams = ModContactusHelper::getModuleParams();
		return $moduleParams['mailRecipient'];
    }

    private static function getMailSubject() {
    	$moduleParams = ModContactusHelper::getModuleParams();
		return $moduleParams['mailSubject'];
    }

    public static function validateCaptcha($captcha) {
    	$url = 'https://www.google.com/recaptcha/api/siteverify';
    	$captchaServerKey = ModContactusHelper::getCaptchaServerKey();

        if (ModContactusHelper::isCaptchaUseClientIp()) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $fields = array(
                    'secret'    => urlencode($captchaServerKey),
                    'response'  => urlencode($captcha),
                    'remoteip'  => urlencode($ip)
            );
        } else {
            $fields = array(
                    'secret'    => urlencode($captchaServerKey),
                    'response'  => urlencode($captcha)
            );
        }

                $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($fields)
                )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return json_decode($result, true)['success'] == true;
    }
}