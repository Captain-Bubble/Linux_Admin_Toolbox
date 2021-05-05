// ------------------- JS ----------------
import $ from 'jquery';


$(document).ready(function () {

    $('#activeServer').change(function () {
        let v = $(this).val();

        $.ajax('/session/set/activeServer', {
            data:{'val':v},
            success:function () {
                window.location.reload();
            }
        });

    });

});