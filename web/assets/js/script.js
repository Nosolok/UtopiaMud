/**
 * Created by Rottenwood on 16.07.14.
 */

$(document).ready(function () {
    var chat = $('#chatinput');
    var game = $('#game');
    var conn = new ab.Session('ws://localhost:8080',
        //    var conn = new ab.Session('ws://74.122.199.113:8080',
        function () {
            console.log("Соединение установлено");
            conn.subscribe('system.channel', function (topic, data) {
                console.log('New article published to category "' + topic + '" : ' + data.title);
            });
            conn.subscribe('personal.' + hash, function (topic, data) {
                console.log('' + topic + ':');
                console.log(data);

                // обработка входящей информации
                render(data);


            });

            var send = {};
            send["CMD"] = "look";

            conn.publish('system.channel', "HASH:::" + hash);
            conn.publish('personal.' + hash, send);

            // отправление команды
            $('#chatform').submit(function (event) {
                event.preventDefault();
                var lastcommand = chat.val();

                // Очистка чата
                chat.val('');
//                session.publish('personal.' + hash, ['CMD', lastcommand]);
                conn.publish('personal.' + hash, {'CMD': lastcommand});
                // Эхо введенной команды
                game.append("<span class='command'>" + lastcommand + "</span><br>");
                scroll();
            });
        },
        function () {
            console.warn('WebSocket connection closed');
            console.log('Переподключение..');

            // таймер
            var timercount = 20;
            game.append("<span class='command'>Соединение потеряно.<br>Переподключение через <span id='rebootcounter'>" + timercount + "</span> сек</span><br>"
            )
            ;
//            setTimeout(function(){
//                window.location.reload(1);
//            }, 10000);


            var counter = setInterval(timer, 1000); //1000 will  run it every 1 second

            function timer() {
                var rebootcounter = $("#rebootcounter");
                timercount = timercount - 1;
                if (timercount <= 0) {
                    clearInterval(counter);
                    // перезагрузка страницы по истечению таймера
                    window.location.reload(1);
                    return;
                }

                // отображение таймера
                rebootcounter.html(timercount);
            }

        },
        {
            'skipSubprotocolCheck': true,
            'retryDelay': 5000
        }
    );

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
        if (data['n']) {
            game.append("<span class='roomexits'>север ");
        }
        ;
        if (data['s']) {
            game.append("<span class='roomexits'>юг ");
        }
        ;
        if (data['w']) {
            game.append("<span class='roomexits'>запад ");
        }
        ;
        if (data['e']) {
            game.append("<span class='roomexits'>восток ");
        }
        ;
        if (data['u']) {
            game.append("<span class='roomexits'>вверх ");
        }
        ;
        if (data['d']) {
            game.append("<span class='roomexits'>вниз ");
        }
        ;
        game.append("<span class='roomexits'>]<br><br>");
    }

    // Отображение персонажей
    function showplayers(data) {

        console.log(data);
        jQuery.each(data, function (name, data) {
            console.log(data);
            game.append("<span class='players'>" + name + ", " + data["race"] + "</span><br>");
            return (this != "three"); // will stop running after "three"
        });

        game.append("<br>");
    }
});