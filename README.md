
Usage
-----

* Create virtual host with document root set on root folder of project
* Import file dump.sql
* Change user and password in configs/main.php
* Run tests: phpunit app/frame/core/tests/EventDispatcherTest.php, phpunit app/model/production3/test/OnSubmitCommentFormTest.php
* Run in console: mysql -uroot -p forum -e 'INSERT INTO `observers` SET `event_type`="onSubmit", `observer_name`="OnSubmitCommentForm"'
* Go to index page of website
* Type comments with emotions :), :(
