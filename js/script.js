

function onCheckForm() {
	var isCheck = $('#formCheck').is(':checked');

	$('.form-control.error').attr('placeholder', '');

	if (isCheck == true) {
		$('.form-bg input[type=submit]').removeAttr('disabled');

	} else {
		$('.form-bg .btn').attr('disabled', 'disabled');
	}
}
