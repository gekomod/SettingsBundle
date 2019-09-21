# SettingsBundle

Ustawienia z poziomu Sonata Admin + Mysql - Każdą dodaną opcje do bazy można odczytać w Twig Oraz w Kontrolerze.


Read From Twig
```
{{ 'debug'|settings_get }}
```

Read From Controller

```
$this->container->get('settings.new')->getSettings('debug');
```