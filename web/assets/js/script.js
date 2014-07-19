/**
 * Created by Rottenwood on 16.07.14.
 */

$(document).ready(function () {
    var chat = $('#chatinput');
    var game = $('#game');
    var conn = new ab.Session('ws://' + serverip + ':6661',
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

            conn.publish('system.channel', {'HASH': hash});
            conn.publish('personal.' + hash, send);

            // отправление команды
            $('#chatform').submit(function (event) {
                event.preventDefault();
                var lastcommand = chat.val();

                // Очистка чата
                chat.val('');
                conn.publish('personal.' + hash, {'CMD': lastcommand});

                // Эхо введенной команды
//                game.append("<span class='command'>" + lastcommand + "</span><br>");
//                scroll();
            });
        },
        function () {
            console.warn('WebSocket connection closed');
            console.log('Переподключение..');

            // таймер
            var timercount = 1;
            scroll();

            var counter = setInterval(timer, 1000);

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

            game.append("<span class='command'>Соединение потеряно.<br>Переподключение через <span id='rebootcounter'>" + timercount + "</span> сек</span><br><br>"
            );

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
        //*** ответ от сервера: ошибки
        if (data['message'] == "0:1") {
            game.append("<br><span class='plaintext'>Команда не найдена!</span><br><br>");
        } else if (data['message'] == "0:2:1") {
            game.append("<br><span class='plaintext'>Ты не видишь ничего похожего на &quot;" + data['object'] + "&quot;.</span><br><br>");
        } else if (data['message'] == "0:3") {
            game.append("<br><span class='plaintext'>Ты не можешь двигаться в данном направлении.</span><br><br>");
        } else if (data['message'] == "0:4:1") {
            game.append("<br><span class='plaintext'>Что ты хочешь сказать?</span><br><br>");
        }

        //*** ответ от сервера: системные действия
        if (data['message'] == "0:5") {
            var url = "logout";
            $(location).attr('href', url);
        } else if (data['message'] == "0:5:1") {
            game.append("<br><span class='plaintext'>Для выхода введи команду &quot;конец&quot; (quit) целиком.</span><br><br>");
        } else if (data['message'] == "0:6:1") {
            game.append("<span class='plaintext'>" + data['who'] + " вошел в наш мир.</span><br><br>");
        } else if (data['message'] == "0:6:2") {
            game.append("<span class='plaintext'>" + data['who'] + " покинул этот мир.</span><br><br>");
        }

        //*** ответ от сервера: результаты команд
        if (data['message'] == "1:1") {
            game.append("<br><span class='plaintext'>Ты осмотрелся.</span><br><br>");
        } else if (data['message'] == "1:2") {
            game.append("<br><span class='plaintext'>Ты обратил взгляд на " + data['object'] + ".</span><br>" + data['desc'] + "<br><br>");
        // уходит
        } else if (data['message'] == "1:3:1") {
            game.append("<span class='plaintext'>" + data['who'] + " ушел на север.</span><br><br>");
        } else if (data['message'] == "1:3:2") {
            game.append("<span class='plaintext'>" + data['who'] + " ушел на восток.</span><br><br>");
        } else if (data['message'] == "1:3:3") {
            game.append("<span class='plaintext'>" + data['who'] + " ушел на юг.</span><br><br>");
        } else if (data['message'] == "1:3:4") {
            game.append("<span class='plaintext'>" + data['who'] + " ушел на запад.</span><br><br>");
        } else if (data['message'] == "1:3:5") {
            game.append("<span class='plaintext'>" + data['who'] + " ушел наверх.</span><br><br>");
        } else if (data['message'] == "1:3:6") {
            game.append("<span class='plaintext'>" + data['who'] + " ушел вниз.</span><br><br>");
        // приходит
        } else if (data['message'] == "1:4:1") {
            game.append("<span class='plaintext'>" + data['who'] + " пришел с юга.</span><br><br>");
        } else if (data['message'] == "1:4:2") {
            game.append("<span class='plaintext'>" + data['who'] + " пришел с запада.</span><br><br>");
        } else if (data['message'] == "1:4:3") {
            game.append("<span class='plaintext'>" + data['who'] + " пришел с севера.</span><br><br>");
        } else if (data['message'] == "1:4:4") {
            game.append("<span class='plaintext'>" + data['who'] + " пришел с востока.</span><br><br>");
        } else if (data['message'] == "1:4:5") {
            game.append("<span class='plaintext'>" + data['who'] + " пришел снизу.</span><br><br>");
        } else if (data['message'] == "1:4:6") {
            game.append("<span class='plaintext'>" + data['who'] + " пришел сверху.</span><br><br>");
        // сказать
        } else if (data['message'] == "2:1") {
            game.append("<span class='plaintext'>" + data['who'] + " сказал: <span class='chatsayphrase'>" + data['say'] + "</span></span><br><br>");
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
        if (data['system2']) {
            game.append("<br><span class='plaintext'>" + data['system2'] + "</span><br><br>");
        }

        scroll();
    }

    // Отображение выходов
    function showexits(data) {
        var exitways = "";
        if (data['n']) {
            exitways = "север ";
        }
        if (data['s']) {
            exitways = exitways + "юг ";
        }
        if (data['w']) {
            exitways = exitways + "запад ";
        }
        if (data['e']) {
            exitways = exitways + "восток ";
        }
        if (data['u']) {
            exitways = exitways + "вверх ";
        }
        if (data['d']) {
            exitways = exitways + "вниз ";
        }
        game.append("<span class='roomexits'>[ Выходы: " + exitways + "]</span><br><br>");
    }

    // Отображение персонажей
    function showplayers(data) {

        console.log(data);
        jQuery.each(data, function (name, data) {
            console.log(data);
            game.append("<span class='players'>" + name + ", " + data["race"] + " стоит тут.</span><br>");
            return (this != "three"); // will stop running after "three"
        });

        game.append("<br>");
    }
});