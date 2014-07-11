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
            if (args[0] == "reloadpage") {
                location.reload();
            } else {
                game.append("<br><span class='plaintext'>" + args[0] + "</span><br><br>");
                scroll();
            }
            console.log(args);
        }

        session.subscribe('system.channel', onevent);
        // отправка хэша
        session.publish('system.channel', ['HASH:::' + hash]);

        // Подписка на личный канал
        session.subscribe('personal.' + hash, function (data) {
            console.log(data);
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

    // Скроллинг чата вниз
    function scroll() {
        var elem = document.getElementById('game');
        elem.scrollTop = elem.scrollHeight;
    }

    // Обработка входящей информации
    function render(data) {
        console.log("Входные данные для рендера:");
        console.log(data);

        //*** ответ от сервера: ошибки
        if (data['message'] == "0:1") {
            game.append("<br><span class='plaintext'>Команда не найдена!</span><br><br>");
        } else if (data['message'] == "0:3") {
            game.append("<br><span class='plaintext'>Вы не можете двигаться в данном направлении.</span><br><br>");
        }

        //*** ответ от сервера: системные действия
        if (data['message'] == "0:5") {
            var url = "logout";
            $(location).attr('href', url);
        }
        if (data['message'] == "0:5:1") {
            game.append("<br><span class='plaintext'>Для выхода введите команду &quot;конец&quot; (quit) целиком.</span><br><br>");
        }

        //*** ответ от сервера: результаты команд
        if (data['message'] == "1:1") {
            game.append("<br><span class='plaintext'>Вы осмотрелись.</span><br><br>");
        } else if (data['message'] == "1:2") {
            game.append("<br><span class='plaintext'>Вы обратили взгляд на объект.</span><br><br>");
        }

        if (data['roomname']) {
            game.append("<br><span class='roomname'>" + data['roomname'] + "</span><br><span class='roomdesc'>" + data['roomdesc'] + "</span><br><br>");
        }
        if (data['exits']) {
            showexits(data['exits']);
        }
        if (data['players']) {
            showplayers(data['players']);
        }
        if (data['system']) {
            game.append("<br><span class='plaintext'>" + data['system'] + "</span><br><br>");
        }

        scroll();
    }

    // Отображение выходов
    function showexits(data) {
        game.append("<span class='roomexits'>[ Выходы: ");
        if (data['n']) {game.append("<span class='roomexits'>север ");};
        if (data['s']) {game.append("<span class='roomexits'>юг ");};
        if (data['w']) {game.append("<span class='roomexits'>запад ");};
        if (data['e']) {game.append("<span class='roomexits'>восток ");};
        if (data['u']) {game.append("<span class='roomexits'>вверх ");};
        if (data['d']) {game.append("<span class='roomexits'>вниз ");};
        game.append("<span class='roomexits'>]<br><br>");
    }

    // Отображение персонажей
    function showplayers(data) {

            console.log(data);
        jQuery.each(data, function(name, data) {
            console.log(data);
        game.append("<span class='players'>" + name + ", " + data["race"] + "</span><br>");
            return (this != "three"); // will stop running after "three"
        });

        game.append("<br>");
    }

});
