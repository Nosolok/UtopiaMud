/**
 * Created by Rottenwood on 16.07.14.
 */

$(document).ready(function () {
    var chat = $('#chatinput');
    var game = $('#game');

    var N = 10;
    var Nc = 0;
    var command_history = new Array(N);
    var i;

    for (i = 0; i < N; i++) {
        command_history[i] = "";
    }

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
                addCommandToHistory(lastcommand);

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
            game.append("<span class='plaintext'>Ты не можешь двигаться в данном направлении.</span><br><br>");
        } else if (data['message'] == "0:4:1") {
            game.append("<br><span class='plaintext'>Что ты хочешь сказать?</span><br><br>");
        } else if (data['message'] == "0:4:2") {
            game.append("<br><span class='plaintext'>Что ты хочешь крикнуть?</span><br><br>");
        } else if (data['message'] == "0:4:3") {
            game.append("<br><span class='plaintext'>О чем ты хочешь написать в общий чат?</span><br><br>");
        }

        //*** ответ от сервера: системные действия
        if (data['message'] == "0:5") {
            var url = "logout";
            $(location).attr('href', url);
        } else if (data['message'] == "0:5:1") {
            if (data['cmdlang']) {
                game.append("<br><span class='plaintext'>Введите команду &quot;" + data['cmdlang'] + "&quot; (" + data['cmd'] + ") целиком.</span><br><br>");
            } else {
                game.append("<br><span class='plaintext'>Please enter full command &quot;" + data['cmd'] + "&quot;.</span><br><br>");

            }
        } else if (data['message'] == "0:6:1") {
            game.append("<span class='plaintext'>" + data['who'] + " вошел в наш мир.</span><br><br>");
        } else if (data['message'] == "0:6:2") {
            game.append("<span class='plaintext'>" + data['who'] + " покинул этот мир.</span><br><br>");
        // закрытая дверь
        } else if (data['message'] == "0:7:1") {
            game.append("<span class='plaintext'>" + data['gate'] + " - закрыто.</span><br><br>");
        } else if (data['message'] == "0:7:2") {
            game.append("<span class='plaintext'>Что ты хочешь открыть?</span><br><br>");
        } else if (data['message'] == "0:7:3") {
            game.append("<span class='plaintext'>Ты открыл " + data['object'] + ".</span><br><br>");
        } else if (data['message'] == "0:7:4") {
            game.append("<span class='plaintext'>Ты закрыл " + data['object'] + ".</span><br><br>");
        } else if (data['message'] == "0:7:5") {
            game.append("<span class='plaintext'>" + data['object'] + " - уже открыто.</span><br><br>");
        } else if (data['message'] == "0:7:6") {
            game.append("<span class='plaintext'>" + data['object'] + " - уже закрыто.</span><br><br>");
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
            // крикнуть
        } else if (data['message'] == "2:2") {
            game.append("<span class='plaintext'>" + data['who'] + " крикнул: <span class='chatshoutphrase'>" + data['shout'] + "</span></span><br><br>");
            // общий чат
        } else if (data['message'] == "2:3") {
            game.append("<span class='plaintext'><span class='chatoocname'>[" + data['who'] + "]</span>: <span class='chatoocphrase'>" + data['ooc'] + "</span></span><br><br>");
            // who
        } else if (data['message'] == "3:1") {
            game.append("<span class='plaintext'>В данный момент в игре находятся:<br></span>");
            jQuery.each(data['whoonline'], function (name, data) {
                game.append("<span class='whoonlinelist'>" + data["race"] + " " + name + "</span><br>");
            });
            game.append("<br><span class='plaintext'>Всего игроков: " + data["whoonlinecount"] + "</span><br><br>");
            // погода
        } else if (data['worldweather'] == "4:1") {
            game.append("<span class='plaintext'>Солнце встает на востоке.</span><br><br>");
        } else if (data['worldweather'] == "4:2") {
            game.append("<span class='plaintext'>Пошел дождь.</span><br><br>");
        } else if (data['worldweather'] == "4:3") {
            game.append("<span class='plaintext'>Солнце садится на западе. Начинается ночь.</span><br><br>");
        } else if (data['worldweather'] == "4:4") {
            game.append("<span class='plaintext'>Ветер усиливается.</span><br><br>");
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
        if (data['mobs']) {
            showmobs(data['mobs']);
        } else if (data['players']) {
            game.append("<br>");
        }
        if (data['system']) {
            game.append("<br><span class='plaintext'>" + data['system'] + "</span><br><br>");
        }
        if (data['system2']) {
            game.append("<br><span class='plaintext'>" + data['system2'] + "</span><br><br>");
        }
        if (data['minimap']) {
            console.log(data);
            var mapdata = data['minimap'];
            // отбор нужных комнат для отрисовки
            if (mapdata['wn']) {
                mapdata['nw'] = mapdata['wn'];
            }
            if (mapdata['ws']) {
                mapdata['sw'] = mapdata['ws'];
            }
            if (mapdata['en']) {
                mapdata['ne'] = mapdata['en'];
            }
            if (mapdata['es']) {
                mapdata['se'] = mapdata['es'];
            }
            if (mapdata['nnw']) {
                mapdata['nwn'] = mapdata['nnw'];
            }
            if (mapdata['nne']) {
                mapdata['nen'] = mapdata['nne'];
            }
            if (mapdata['ssw']) {
                mapdata['sws'] = mapdata['ssw'];
            }
            if (mapdata['sse']) {
                mapdata['ses'] = mapdata['sse'];
            }
            if (mapdata['wwn']) {
                mapdata['nww'] = mapdata['wwn'];
            }
            if (mapdata['wws']) {
                mapdata['sww'] = mapdata['wws'];
            }
            if (mapdata['een']) {
                mapdata['nee'] = mapdata['een'];
            }
            if (mapdata['ees']) {
                mapdata['see'] = mapdata['ees'];
            }
            if (mapdata['enn']) {
                mapdata['nen'] = mapdata['enn'];
            }
            if (mapdata['wnn']) {
                mapdata['nwn'] = mapdata['wnn'];
            }
            if (mapdata['ess']) {
                mapdata['ses'] = mapdata['ess'];
            }
            if (mapdata['wss']) {
                mapdata['sws'] = mapdata['wss'];
            }

            if (mapdata['ene']) {
                mapdata['nee'] = mapdata['ene'];
            }
            if (mapdata['ese']) {
                mapdata['see'] = mapdata['ese'];
            }
            if (mapdata['wnw']) {
                mapdata['nww'] = mapdata['wnw'];
            }
            if (mapdata['wsw']) {
                mapdata['sww'] = mapdata['wsw'];
            }

            drawmap(
                mapdata['nwwn'], mapdata['nwn'], mapdata['nn'], mapdata['nen'], mapdata['neen'],
                mapdata['nww'], mapdata['nw'], mapdata['n'], mapdata['ne'], mapdata['nee'],
                mapdata['ww'], mapdata['w'], mapdata['r'], mapdata['e'], mapdata['ee'],
                mapdata['sww'], mapdata['sw'], mapdata['s'], mapdata['se'], mapdata['see'],
                mapdata['swws'], mapdata['sws'], mapdata['ss'], mapdata['ses'], mapdata['sees']
            );
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
        if (!data['n'] && !data['s'] && !data['w'] && !data['e'] && !data['u'] && !data['d']) {
            exitways = exitways + "нет ";
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

    }

    // Отображение мобов
    function showmobs(data) {
        console.log(data);
        jQuery.each(data, function (name, data) {
            console.log(data);
            game.append("<span class='mobs'>" + data["short"] + "</span><span class='mobsalias'> [" + data["name"] + "]</span><br>");
        });

        game.append("<br>");
    }

    //######## История команд

    // добавление команды в историю
    function addCommandToHistory(command) {
        i = 1;
        while (command_history[i] != "" && i < N) i++;
        if (i == N) {
            for (i = 0; i < N - 1; i++)
                command_history[i] = command_history[i + 1];
            command_history[N - 1] = command;
            Nc = N - 1;
        }
        else {
            command_history[i] = command;
            Nc = i;
        }
    }

    // хоткеи
    $("body").keydown(function (key) {
        if (key.which == 38 && Nc > 0) {
            chat.val(command_history[Nc])
            Nc--;
            return false;
        }
        if (key.which == 40) {
            chat.val("");
            if (command_history[Nc + 1]) {
                Nc++;
                chat.val(command_history[Nc])
                return false;
            }
        }

    });


    // Селекторы элементов миникарты
    var map1 = $('#nwwn');
    var map2 = $('#nwn');
    var map3 = $('#nn');
    var map4 = $('#nen');
    var map5 = $('#neen');

    var map6 = $('#nww');
    var map7 = $('#nw');
    var map8 = $('#n');
    var map9 = $('#ne');
    var map10 = $('#nee');

    var map11 = $('#ww');
    var map12 = $('#w');
    var map13 = $('#r');
    var map14 = $('#e');
    var map15 = $('#ee');

    var map16 = $('#sww');
    var map17 = $('#sw');
    var map18 = $('#s');
    var map19 = $('#se');
    var map20 = $('#see');

    var map21 = $('#swws');
    var map22 = $('#sws');
    var map23 = $('#ss');
    var map24 = $('#ses');
    var map25 = $('#sees');

    // функции для обработки карт
    function drawmap(m1,m2,m3,m4,m5,m6,m7,m8,m9,m10,m11,m12,m13,m14,m15,m16,m17,m18,m19,m20,m21,m22,m23,m24,m25) {
        map1.html("<img src='assets/img/map/" + m1 + ".png'>");
        map2.html("<img src='assets/img/map/" + m2 + ".png'>");
        map3.html("<img src='assets/img/map/" + m3 + ".png'>");
        map4.html("<img src='assets/img/map/" + m4 + ".png'>");
        map5.html("<img src='assets/img/map/" + m5 + ".png'>");

        map6.html("<img src='assets/img/map/" + m6 + ".png'>");
        map7.html("<img src='assets/img/map/" + m7 + ".png'>");
        map8.html("<img src='assets/img/map/" + m8 + ".png'>");
        map9.html("<img src='assets/img/map/" + m9 + ".png'>");
        map10.html("<img src='assets/img/map/" + m10 + ".png'>");

        map11.html("<img src='assets/img/map/" + m11 + ".png'>");
        map12.html("<img src='assets/img/map/" + m12 + ".png'>");
        map13.html("<img src='assets/img/map/" + m13 + ".png'>");
        map14.html("<img src='assets/img/map/" + m14 + ".png'>");
        map15.html("<img src='assets/img/map/" + m15 + ".png'>");

        map16.html("<img src='assets/img/map/" + m16 + ".png'>");
        map17.html("<img src='assets/img/map/" + m17 + ".png'>");
        map18.html("<img src='assets/img/map/" + m18 + ".png'>");
        map19.html("<img src='assets/img/map/" + m19 + ".png'>");
        map20.html("<img src='assets/img/map/" + m20 + ".png'>");

        map21.html("<img src='assets/img/map/" + m21 + ".png'>");
        map22.html("<img src='assets/img/map/" + m22 + ".png'>");
        map23.html("<img src='assets/img/map/" + m23 + ".png'>");
        map24.html("<img src='assets/img/map/" + m24 + ".png'>");
        map25.html("<img src='assets/img/map/" + m25 + ".png'>");
    }

});