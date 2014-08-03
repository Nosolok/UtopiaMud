# Artopia (Артопия) ![Текущая версия](http://img.shields.io/badge/Текущая версия Artopia- v0.1.1 -brightgreen.svg)
## Современный веб-движок для MUD

#### Рабочая версия муда: http://utopia.ml/mud

Аналитика: [![SensioLabsInsight](https://insight.sensiolabs.com/projects/a6196715-eb57-4447-a423-71127f7ed827/mini.png)](https://insight.sensiolabs.com/projects/a6196715-eb57-4447-a423-71127f7ed827) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Rottenwood/UtopiaMud/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Rottenwood/UtopiaMud/?branch=master)

[Форум](https://github.com/Rottenwood/UtopiaMud/issues?labels=%D0%9E%D0%B1%D1%81%D1%83%D0%B6%D0%B4%D0%B5%D0%BD%D0%B8
%D0%B5&page=1&state=open)
| [Wiki](https://github.com/Rottenwood/UtopiaMud/wiki)
| [Легкая версия ролевых правил GURPS](https://cloud.mail.ru/public/47c25a67bed8/gurps-rules-lite.pdf)
| [Планы на будущее](https://github.com/Rottenwood/UtopiaMud/blob/master/FUTURE.md)
| [История версий](https://github.com/Rottenwood/UtopiaMud/blob/master/CHANGELOG.md)

> MUD (Multi User Dungeon) — текстовая многопользовательская компьютерная игра, в которой присутствуют элементы ролевой игры, приключений, боевой системы и обязательно чат, как правило разделённый на каналы. Традиционно для передачи сообщений используется протокол telnet.

**Artopia**, в свою очередь, являются программным решением на языке PHP для создания муд-миров. Основной упор делается на простоту настройки. Легкая установка позволит любому желающему начать создавать свое виртуальное пространство, в то время как система интеграции позволит объединять пользовательские миры в единую вселенную.

### Документация

[Описание процесса установки](https://github.com/Rottenwood/UtopiaMud/wiki/%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0)

### Особенности
* Игра происходит через браузер, а не через telnet-клиент.
* Все настройки игры, параметры, заклинания, локации и предметы хранятся в удобных для редактирования текстовых файлах, откуда могут быть легко импортированы в базу данных.
* Удобный внутриигровой редактор зон, предметов, монстров. Возможность загрузки зон с удаленных хостов.
* Игроки мудов на движке Artopia будут иметь возможность перемещаться из одного сервера в другой, без необходимости повторной регистрации.
* Перемещение персонажей происходит не только в трехмерном пространстве, но и между слоями мира. Не находя брода через бурную реку, существо может переместиться на соседний слой пространства, где вместо реки увидит лишь неглубокий ручей.
* Неоднородность мира приводит к тому, что персонаж может оказаться как в средневековье, так и на борту космического аппарата. При этом система мирового баланса не даст возможности, например, эффективно использовать лучевые бластеры и двуручную кувалду. В мире магии не будет работать двигатель внутреннего сгорания, а в современном мегаполисе магия останется уделом фокусников.
* Для расчета умений используется система перков и очков характеристик. Нет необходимости перекидывать кубики, и надеяться на удачу в таком важном вопросе как развитие персонажа. Отсутствие уровневого деления и большое количество умений позволяет эффективно взаимодействовать персонажам с различным игровым опытом.
* Отказавшись от процентного расчета мастерства в умениях, мы получили возможность оставить занудную прокачку скиллов в прошлом. Персонаж "качается" сам по себе, не требуя от игрока рутинных действий.
* Погода в мире меняется по своим законам, влияет на магию и во многом определяет ее. Например, запустить фаербол под проливным дождем сложнее чем при ясной солнечной погоде.
* Рэндомно генерируемый лут (в стиле дьябло), таблицы дропа.
* Добыча ресурсов и крафт.

### Ход работы
Идет работа над версией **v0.1.1** | [задачи оставшиеся до релиза](https://github.com/Rottenwood/UtopiaMud/issues?milestone=1)

### Функционал
* Языковые настройки вынесены в отдельный файл, позволяя перевести команды и любой отображаемый текст на другой язык.
* Автоопределение сокращенных команд. Игрок может как угодно сократить команду, в то время как сервер поймет ее. При этом реализована возможность некоторым командам не сокращаться (например "quit").
* Фоновая перезагрузка, практически моментальная и без необходимости переподключения.

### Конфигурационные файлы
* [Команды](https://github.com/Rottenwood/UtopiaMud/blob/master/src/Rottenwood/UtopiaMudBundle/Resources/config/commands.yml)
* [Расы](https://github.com/Rottenwood/UtopiaMud/blob/master/src/Rottenwood/UtopiaMudBundle/Resources/races/races.yml)
* [Список зон](https://github.com/Rottenwood/UtopiaMud/blob/master/src/Rottenwood/UtopiaMudBundle/Resources/zones/zonelist.yml)
* [Пример зоны "Средневековый город"](https://github.com/Rottenwood/UtopiaMud/blob/master/src/Rottenwood/UtopiaMudBundle/Resources/zones/medievaltown/rooms.yml)
* [Типы комнат](https://github.com/Rottenwood/UtopiaMud/blob/master/src/Rottenwood/UtopiaMudBundle/Resources/types/roomtypes.yml)

### Настройки
*В данном разделе будут собираться и описываться необходимые пользовательские настройки [on/off]*

* Отображение введенных команд
* Выбор цвета для всех элементов, цветовые схемы.
* Размер и тип шрифта
* включение/отключение графики (например, изображение персонажа при взгляде на него)
