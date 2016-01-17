<?php 
// No direct access
defined('_JEXEC') or die;

if ($useCaptcha) {
	$doc->addScript('//www.google.com/recaptcha/api.js?hl=ru');
}

$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');

$doc->addScript('//code.jquery.com/jquery-2.2.0.min.js');
$doc->addScript('//code.jquery.com/ui/1.11.4/jquery-ui.min.js');
$js = 'jQuery.noConflict();';
$doc->addScriptDeclaration($js);
$doc->addStyleSheet('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css');
$doc->addStyleSheet( $modBase . '/css/main.css');
?>

<?php if ($usePopup) { ?>
<a href="#" id="dialog_link"><?php echo $btnPopupTitle; ?></a>
<?php } ?>

<div id="contactus" title="<?php echo $popupTitle; ?>" <?php if($usePopup) { ?> style="display:none;" <?php } ?> >
	<form id="contactus-form" method="POST" action="" accept-charset="UTF-8">
		<fieldset form="contactus-form">

			<label class="icon" for="name"><i class="fa fa-user"></i></label>
  			<input type="text" name="cu-name" id="cu-name" placeholder="<?php echo $fldNameTitle; ?>" required/> <br />

  			<label class="icon" for="cu-email"><i class="fa fa-at"></i></label>
  			<input type="email" name="cu-email" id="cu-email" placeholder="<?php echo $fldEmailTitle; ?>" required/> <br />

			<label for="cu-message" class="title-message"><?php echo $fldMessageTitle; ?></label> <br />
			<textarea id="cu-message" name="cu-message" required></textarea> <br />

			<?php if ($useCaptcha) {?>
			<!--<label for="captcha"><?php echo $fldCaptchaTitle; ?></label> <br />-->
			<div id="captcha" class="g-recaptcha" 
								data-sitekey="<?php echo $captchaFrontKey; ?>"></div>
			<?php }?>
			<input
			style="	background-color:    <?php echo $btnSubmitStyle['backgroundColor']; ?>;
					color: 				 <?php echo $btnSubmitStyle['textColor']; ?>;
					border-bottom-color: <?php echo $btnSubmitStyle['bottomBackgroundColor']; ?>;"
			id="cu-submit" type="submit" value="<?php echo $btnSubmitTitle; ?>">
		</fieldset>
	</form>	
</div>

<script type="text/javascript">
jQuery(document).ready(function () {

    jQuery('#dialog_link').click(function () {
        jQuery('#contactus').dialog({
        	    width: 500,
       			modal: true
        });
        return false;
    });

    jQuery('#contactus-form').submit(function (e) {

    	if (!grecaptcha.getResponse()) {
    		e.preventDefault();
    		alert('Пожалуйста, докажите, что Вы не робот.');
    		return;
    	}

    	var emailValue = jQuery('#cu-email').val(),
    	nameValue = jQuery('#cu-name').val(),
    	<?php if ($useCaptcha) { ?>
    		recaptchaValue = jQuery('#g-recaptcha-response').val(),
    	<?php } ?>	
		messageValue = jQuery('#cu-message').val();

		var request = {
				'option'  : 'com_ajax',
				'module'  : 'contactus',
				'method'  : 'validateAndSend',
				'name'	  :  nameValue,
				'email'   :  emailValue,
				'message' :  messageValue,
				<?php if ($useCaptcha) { ?>
				'captcha' :  recaptchaValue,
				<?php } ?>
				'format'  : 'json'
			};

		jQuery.ajax({
			type        : 'POST',
			data   		: request,
			success: function (response) {
				if (response.data) {
					jQuery('form#contactus-form').slideUp("fast", function() {				   
						jQuery(this).before('<div class="alert alert-success"><strong>Спасибо!</strong> Ваш запрос был отправлен. С Вами скоро свяжутся по адресу электронной почты, указанному в запросе.</div>');
					});
				} else {
					grecaptcha.reset();
					alert('Отправка не удалась! Попробуйте снова, пожалуйста.')
				}
				console.log(response.data);
			},
			error: function(response) {
				console.log(response);
	        }
		});

		e.preventDefault();
    });
});
</script>