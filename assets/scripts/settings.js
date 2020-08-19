import $ from 'jquery';
$(document).ready(function (){
	$(document).on('click', '.user', function (){
		$(document).find('.user').removeClass('active');
		$(this).addClass('active');
		$.ajax({
			url:"{{ url('settings.useracc.ajax', {'id':'0'}) }}".replace('0', $(this).data('id')),
			success:function (d) {
				if (d && d.data) {
					$('#content').html(d.content);
				}
			}
		})
	});
});