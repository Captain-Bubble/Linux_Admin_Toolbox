// ------------------- JS ----------------
import $ from 'jquery';


$(document).ready(function () {

    $('#activeServer').change(function () {
        let v = $(this).val();

        $.ajax('/session/set/activeServer', {
            method:'POST',
            data:{'val':v},
            success:function () {
							$('#clearCache').trigger('click');
            }
        });

    });

    $('#clearCache').click(function () {
				$.ajax('/session/clear', {
						method: 'POST',
						success: function () {
								window.location.reload();
						}
				});
    })

});