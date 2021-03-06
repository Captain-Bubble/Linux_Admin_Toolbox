/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// ------------------ CSS ----------------
// import '../css/login.css';

// ------------------- JS ----------------
import $
	from 'jquery';

$(document).ready(function () {
	$(document).on('click', '.user', function () {
		$(document).find('.user').removeClass('active');
		$(this).addClass('active');
		$.ajax({
			method: "POST",
			url: url_load_edit_user,
			data: {'id': $(this).data('id')},
			success: function (d) {
				$('#content').html(d);
			}
		})
	});

	$(document).on('click', '.sendSubmitEditUser', function (e) {
		e.preventDefault();
		let dat = $(this).parents('form').serialize();
		dat += '&id=' + $('.user.active').data('id');
		$.ajax(url_noe_user, {
			method: 'POST',
			data: dat,
			success: function (data) {

			}
		});
	});
});

