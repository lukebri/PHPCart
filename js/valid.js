$(function() {
	var expressions = {
		first_name: /^[a-z]+([\-']|\s)?[a-z]+$/i,
		email: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/,
		message: /^\w{4,}/
	};
	$('#contactbox form').submit(function(e) {
		e.preventDefault();
		var valid = true;
		for(var i = 0; i < this.elements.length; i++) {
			var field = this.elements[i];
			if(field.type == 'text' || field.type == 'textarea') {
				if(!expressions[field.name].test($(field).val())) {
					alert('Invalid: ' + field.name.replace('_', ' ').toUpperCase() + '.');
					field.focus();
					valid = false;
					return false;
				}
			}
		}
		if(valid) {
			$.post('mail.php', $(this).serialize(), function(data) {
				if(data.success) {
					$('#contactbox form').fadeOut();
					$('div#sent').delay(800).fadeIn();
				} else {
					alert('There was an error submitting.');
				}
			}, 'json');
			return false;
		}
	});
});