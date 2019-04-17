# Base settings

Набор классов, представлений и компонентов для разворачивания базового сайта.

### Установка

Выгрузить компоненты VueJs

`php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force`

Выгрузить конфигурацию для галлереи

`php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=config`

Что бы создать нужные модели и шаблоны есть команда
`php artisan make:base-settings` флаг `--views` говорит выгрузить только шаблоны, флаг `--force` говорит заменить шаблоны без подтверждения. На модели всегда спрашивается разрешение на замену.