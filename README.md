Подготовка к прохождению теста
==============================

Получив архив с кодом данного "проекта", вам нужно:

* Распаковать файлы на диск
* Создать пустую базу данных (mysql,postgresql)
* В терминале перейти в директорию проекта
* Выполнить команду composer install (вопросы по установке или использованию composer-а выходят за рамки этой инструкции, если он у вас не установлен и вы не знакомы с тем, что это такое, будем считать, что разобраться с этим самостоятельно - первое тестовое задание)
* В процессе выполнения команды composer install ввести корректные данные для подключения к созданной вами базе данных
* Выполнить команду php app/console doctrine:schema:update --force эта команда создаст в вашей базе данных пустые таблицы, необходимые для дальнейшей работы.
* Применить к вашей базе данных запросы из файла install.sql расположенного в корне проекта.
* Настроить локально виртуальный хост с директорией web проекта, в качестве публичной (предполагается, что для вас не секрет о чем речь).
