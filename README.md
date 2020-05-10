# SettingsBundle

[![Tests][1]][2] [![Symfony 2.x, 3.x and 4.x][7]][8]


[1]: https://travis-ci.org/gekomod/SettingsBundle.svg?branch=master
[2]: https://travis-ci.org/gekomod/SettingsBundle
[7]: https://img.shields.io/badge/symfony-2.x%2C%203.x%20and%204.x-green.svg
[8]: https://symfony.com/


Ustawienia z poziomu Sonata Admin + Mysql - Każdą dodaną opcje do bazy można odczytać w Twig Oraz w Kontrolerze.

Install Settings
```
php bin/console settings:install
```

Read From Twig
```
{{ 'debug'|settings_get }}
```

Read From Controller

```
$this->container->get('settings.new')->getSettings('debug');
```