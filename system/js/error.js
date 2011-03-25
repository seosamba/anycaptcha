$(document).ready(function() {
	var captchaMessage = $(".captcha_plugin_message");
	if(captchaMessage.length) {
		$('html, body').animate({
				scrollTop: captchaMessage.offset().top
			}, 2000
		);
	}
})