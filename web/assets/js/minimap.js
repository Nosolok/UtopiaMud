/**
 * Created by Rottenwood on 27.07.14.
 */

$(document).ready(function () {
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
