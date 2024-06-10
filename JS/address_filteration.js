$(document).ready(function () {
    $('#country_name').on('change', function () {
        country_id = $('#country_name').val();      

        $.ajax({
            url: "ajax/address_filteration.php",
            type: "POST",
            data: {
                country_id: country_id
            },
            cache: false,
            success: function (result) {
                $('#sname').html(result);
            }
        });
    });
    $('#sname').on('change', function () {
        state_id = $('#sname').val();      

        $.ajax({
            url: "ajax/address_filteration.php",
            type: "POST",
            data: {
                state_id: state_id
            },
            cache: false,
            success: function (data) {              
                $('#city_name').html(data);
            }
        });
    });
})