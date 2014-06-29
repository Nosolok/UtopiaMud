//#### Функции

// Отображение выходов
function showexits(data) {
//    alert(data["n"]);
    $('#game').append("<span class='roomexits'>[ Выходы: ");
    if (data['n']) {$('#game').append("<span class='roomexits'>север ");};
    if (data['s']) {$('#game').append("<span class='roomexits'>юг ");};
    if (data['w']) {$('#game').append("<span class='roomexits'>запад ");};
    if (data['e']) {$('#game').append("<span class='roomexits'>восток ");};
    $('#game').append("<span class='roomexits'>]<br><br>");
}

//#### Действия при загрузке страницы
$(document).ready(function() {

    // Инициализация формы для обработки с помощью ajaxForm
    $('#chatform').ajaxForm({
        dataType:  'json',
        success:   processJson,
        beforeSubmit: validate
    });

    // Удержание фокуса на поле ввода
    var el = document.getElementById('game');
    var focusonchatfield = document.getElementById('chatinput');
    focusonchatfield.focus();
    el.onmouseup = function () {
        setTimeout(function () {
            focusonchatfield.focus();
        });
    };

});

// Обработка пользовательского ввода и ответа от сервера
function processJson(data) {
    // 'data' is the json object returned from the server
    var lastcommand = $('#chatinput').val();
    // очистка чата
    $('#chatinput').val('');
    // эхо введенной команды
    $('#game').append("<span class='command'>" + lastcommand + "</span><br>");
    var roomalreadyseen = 0;    // для предотвращения дублирования вывода названия и описания комнаты

    //*** ответ от сервера: ошибки
    if (data['message']=="0:1") {$('#game').append("<br><span class='plaintext'>Команда не найдена!</span><br><br>");}
    if (data['message']=="0:2") {$('#game').append("<br><span class='plaintext'>Ошибка соединения с базой данных (обратитесь к администратору сервера).</span><br><br>");}
    if (data['message']=="0:3") {$('#game').append("<br><span class='plaintext'>Вы не можете двигаться в данном направлении.</span><br><br>");var roomalreadyseen = 1;}
    if (data['message']=="0:4") {$('#game').append("<br><span class='plaintext'>Вы витаете в пустоте.</span><br><br>");}

    //*** ответ от сервера: системные действия
    if (data['message']=="0:5") {
        var url = "logout";
        $(location).attr('href',url);
    }
    if (data['message']=="0:5:1") {$('#game').append("<br><span class='plaintext'>Для выхода введите команду &quot;конец&quot; (quit) целиком.</span><br><br>");}


    //*** ответ от сервера: результаты команд
    if (data['message']=="1:1") {
        var roomalreadyseen = 1;
        $('#game').append("<br><span class='plaintext'>Вы осмотрелись.</span><br><br>");
        if (data['roomnamelook']) {$('#game').append("<br><span class='roomname'>" + data['roomnamelook'] + "</span><br>");}
        if (data['roomdesclook']) {$('#game').append("<span class='roomdesc'>" + data['roomdesclook'] + "</span><br><br>");}
        if (data['exits']) {
            showexits(data['exits']);
        }
    } else if (data['message']=="1:2") {
        $('#game').append("<br><span class='plaintext'>Вы обратили взгляд на объект.</span><br><br>");
    }
    if (data['roomname'] && roomalreadyseen!=1) {$('#game').append("<br><span class='roomname'>" + data['roomname'] + "</span><br>");}
    if (data['roomdesc'] && roomalreadyseen!=1) {$('#game').append("<span class='roomdesc'>" + data['roomdesc'] + "</span><br><br>");}
    if (data['exits'] && roomalreadyseen!=1) {
        showexits(data['exits']);
    }
    if (data['mobs']) {
        var mobs = data['mobs'];

        for (var index in mobs) {
            var mob = mobs[index];
            $('#game').append("<span class='mobshow'>" + mob['ldesc'] + ".</span><br>");
        }
        $('#game').append("<br>");
    };

    // скролл вниз DIV с чатом
    var elem = document.getElementById('game');
    elem.scrollTop = elem.scrollHeight;
}

// Проверка данных перед отправкой на сервер
function validate(formData, jqForm, options) {
    // fieldValue is a Form Plugin method that can be invoked to find the
    // current value of a field
    //
    // To validate, we can capture the values of both the username and password
    // fields and return true only if both evaluate to true

    var usernameValue = $('input[name=chat]').fieldValue();
    // var passwordValue = $('input[name=password]').fieldValue();

    // usernameValue and passwordValue are arrays but we can do simple
    // "not" tests to see if the arrays are empty
    // if (!usernameValue[0] || !passwordValue[0]) {
    if (!usernameValue[0]) {
        // alert('Please enter a value for both Username and Password');
        $('#game').append("<br><br>");
        // скролл вниз DIV с чатом
        var elem = document.getElementById('game');
        elem.scrollTop = elem.scrollHeight;

        return false;
    }
    // alert('Both fields contain values.');
}
