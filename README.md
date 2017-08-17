1. Развернуть базу, находящуюся по адресу: application\database\backup
2. Указать данные для подключения к базе в: application\database\SQL\Mysqli.php
2. Указать в application\core\config.php "DB_LOCAL_PASSWORD" или "DB_SERVER_PASSWORD"
Важно! API ждёт, что будет перенаправлен на http://localhost/light-it/signin/process,
если сайт не будет находиться по адресу localhost/light-it, вряд ли оно вообще заработает.
Если что - залью на хостинг, либо просто поменяйте данные в конфиге на свои.

На всякий случай прикреплю скрин: https://pp.userapi.com/c836332/v836332036/594b8/4Nj3W7pMdWA.jpg

P.S. С БД не стал делать несколько таблиц и внешних ключей, интересен был вариант с рекурсией.