# MaNGOSSite
An unofficial website for [MaNGOS](https://www.getmangos.eu/)

It uses external componets:

* [CodeIgniter 4.0.4](https://www.codeigniter.com/)
* [RedBeanPHP 5.6.2](https://www.redbeanphp.com/)
* [Wowhead Classic Tooltips](https://classic.wowhead.com/tooltips)
* Bootstrap 4.4.1
* Fontawesome 5.7.0
* JQuery 3.4.1
* Popper 1.16.0 (js for tooltip)

## Installation

Clone the repository and install in your web main folder.

Settings for database connection are located in
>app/Config/Constants.php

and are the following

```PHP
//Redbean params
defined('RB_DB_HOST')           || define('RB_DB_HOST','localhost');
defined('RB_DB_USER')           || define('RB_DB_USER','mangos');
defined('RB_DB_PASW')           || define('RB_DB_PASW','mangos');
defined('RB_DB_DBNAME_CHAR')           || define('RB_DB_DBNAME_CHAR','zero_character');
defined('RB_DB_DBNAME_WORLD')           || define('RB_DB_DBNAME_WORLD','zero_world');
defined('RB_DB_DBNAME_REALM')           || define('RB_DB_DBNAME_REALM','zero_realm');
```

## License
[The Unlicense](https://unlicense.org)