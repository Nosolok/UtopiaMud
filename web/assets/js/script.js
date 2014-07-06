/**
 * Created by Rottenwood on 05.07.14.
 */

$(document).ready(function () {
    var connection = new autobahn.Connection({url: 'ws://127.0.0.1:6661/', realm: 'utopia'});
    var chat = $('#chatinput');
    var game = $('#game');
    connection.onopen = function (session) {
        console.log("Соединение установлено");

        // Подписка на канал системных данных
        function onevent(args) {
            game.append('<p>' + args[0] + '</p>');
            console.log(args);
        }

        session.subscribe('system.channel', onevent);
        // отправка хэша
        session.publish('system.channel', ['HASH:::' + hash]);

        // Подписка на личный канал
        session.subscribe('personal.' + hash, function (data) {
            console.log(data);
            console.log(data["message"]);
            render(data);
        });

        $('#chatform').submit(function (event) {
            event.preventDefault();
            var lastcommand = chat.val();

            // Очистка чата
            chat.val('');
            session.publish('personal.' + hash, ['CMD', lastcommand]);
            // Эхо введенной команды
            game.append("<span class='command'>" + lastcommand + "</span><br>");
            scroll();
        });

        // Назначение функции для удаленного выполнение
        // function add2(args) {return args[0] + args[1];}
        // session.register('server.add', add2);

        // Выполнение функции на удаленной стороне
        // session.call('server.add', [2, 3]).then(
        // function (res) {
        //     console.log("Result:", res);
        // });
    };

    // Открытие соединения
    connection.open();


    // Удержание фокуса на поле ввода
    var el = document.getElementById('game');   // почему-то с jQuery не работает
    chat.focus();
    el.onmouseup = function () {
        setTimeout(function () {
            chat.focus();
        });
    };

});
// Скроллинг чата вниз
function scroll() {
    game.scrollTop = game.scrollHeight;
}

// Обработка входящей информации
function render(data) {
    console.log("Входные данные для рендера:");
    console.log(data);

    //*** ответ от сервера: ошибки
    if (data['message'] == "0:1") {
        $('#game').append("<br><span class='plaintext'>Команда не найдена!</span><br><br>");
    }

    scroll();
}

// Обработка пользовательского ввода и ответа от сервера
function processData(data) {
    // 'data' is the json object returned from the server
    var lastcommand = $('#chatinput').val();
    // очистка чата
    $('#chatinput').val('');
    // эхо введенной команды
    $('#game').append("<span class='command'>" + lastcommand + "</span><br>");
    var roomalreadyseen = 0;    // для предотвращения дублирования вывода названия и описания комнаты

    //*** ответ от сервера: ошибки
    if (data['message'] == "0:1") {
        $('#game').append("<br><span class='plaintext'>Команда не найдена!</span><br><br>");
    }
    if (data['message'] == "0:2") {
        $('#game').append("<br><span class='plaintext'>Ошибка соединения с базой данных (обратитесь к администратору сервера).</span><br><br>");
    }
    if (data['message'] == "0:3") {
        $('#game').append("<br><span class='plaintext'>Вы не можете двигаться в данном направлении.</span><br><br>");
        var roomalreadyseen = 1;
    }
    if (data['message'] == "0:4") {
        $('#game').append("<br><span class='plaintext'>Вы витаете в пустоте.</span><br><br>");
    }

    //*** ответ от сервера: системные действия
    if (data['message'] == "0:5") {
        var url = "logout";
        $(location).attr('href', url);
    }
    if (data['message'] == "0:5:1") {
        $('#game').append("<br><span class='plaintext'>Для выхода введите команду &quot;конец&quot; (quit) целиком.</span><br><br>");
    }


    //*** ответ от сервера: результаты команд
    if (data['message'] == "1:1") {
        var roomalreadyseen = 1;
        $('#game').append("<br><span class='plaintext'>Вы осмотрелись.</span><br><br>");
        if (data['roomnamelook']) {
            $('#game').append("<br><span class='roomname'>" + data['roomnamelook'] + "</span><br>");
        }
        if (data['roomdesclook']) {
            $('#game').append("<span class='roomdesc'>" + data['roomdesclook'] + "</span><br><br>");
        }
        if (data['exits']) {
            showexits(data['exits']);
        }
    } else if (data['message'] == "1:2") {
        $('#game').append("<br><span class='plaintext'>Вы обратили взгляд на объект.</span><br><br>");
    }
    if (data['roomname'] && roomalreadyseen != 1) {
        $('#game').append("<br><span class='roomname'>" + data['roomname'] + "</span><br>");
    }
    if (data['roomdesc'] && roomalreadyseen != 1) {
        $('#game').append("<span class='roomdesc'>" + data['roomdesc'] + "</span><br><br>");
    }
    if (data['exits'] && roomalreadyseen != 1) {
        showexits(data['exits']);
    }
    if (data['mobs']) {
        var mobs = data['mobs'];

        for (var index in mobs) {
            var mob = mobs[index];
            $('#game').append("<span class='mobshow'>" + mob['ldesc'] + ".</span><br>");
        }
        $('#game').append("<br>");
    }
    ;

    // скролл вниз DIV с чатом
    var elem = document.getElementById('game');
    elem.scrollTop = elem.scrollHeight;
}