

$(document).ready(function() {

    // Envia un bit para indicar que el usuario esta activo 
    is_online();
    // Repetir cada 10s
    setInterval(function() {
        $('#status_circle').fadeOut(100);
        $('#status_circle').fadeIn(100);
        is_online();
    }, 2000);

});

var STATUS = 0;

function is_online() {
    $.ajax({
        url: 'status_acions/is_online.php',
        type: 'POST',
        success: function(response) {
            if (STATUS == 0) {
                showMessage('Estas online', 'success')
                STATUS = 1;
            }
            $('#status_circle').removeClass('bg-danger');
            $('#status_circle').addClass('bg-success');
            $('#status_text').text('Online');
        },
        error: function() {
            if (STATUS == 1){
                showMessage('Estas offline', 'error')
                STATUS = 0;
            }       
            $('#status_circle').removeClass('bg-success');
            $('#status_circle').addClass('bg-danger');
            $('#status_text').text('Offline');
        },
    });
}
