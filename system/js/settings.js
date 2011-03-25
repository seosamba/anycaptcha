$(document).ready(function() {
	$('#frm_settings').submit(function() {
		$.ajax({
			url  : $(this).attr('action'),
			type : 'post',
			data : $(this).serialize(),
			beforeSend: function() {
				$('#captcha_plugin_settings_overlay').fadeIn('slow');
			},
			success: function(response) {
				$('#captcha_plugin_settings_overlay').html('Saved.');
				//$('#captcha_plugin_settings_overlay').css({background: '#99cc00'});
				$('#captcha_plugin_settings_overlay').slideToggle(3000);
				console.log(response)
			}
		});
		console.log($(this).serialize());
		return false;
	})
})