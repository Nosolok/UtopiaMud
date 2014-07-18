/**
 * Created by Rottenwood on 30.06.14.
 */
$(document).ready(function () {
    $('#loginformbutton').click(function () {
        $("#loginform").submit();
    });
    $('#registrationformbutton').click(function () {
//        var url = document.URL;
        window.location.href = document.URL + "/register";
    });

//    $('#registrationformbutton').click(function () {
//        console.log("test");
//        $.ajax({
//            url: "register/",
//            context: document.body,
//            dataType: "html"
//        }).done(function (data) {
//            $('#form').html(data);
//        });
//    });
});
