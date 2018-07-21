$('#authForm').on('submit',function(event) {
    var $login = $('#loginInput').val();
    var  $password = $('#passwordInput').val();
    $.ajax({
        url: '/login/auth',
        method: 'POST',
        data: {'login': $login, 'password': $password},
        dataType: 'json',
        success: function(response) {
            if(response.success == true) {
                window.location.href = '/person';
            } else {
                alert(response.error);
            }
        },
        error: function(error) {
            alert('Что то пошло не так');
        }
    });
    return false;
});





