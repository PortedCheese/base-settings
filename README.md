# Base settings

Набор классов, представлений и компонентов для разворачивания базового сайта.

### Установка

Выгрузить компоненты VueJs, стили и скрипт.

`php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force`

Комманда make так же предложит наполнить файлы компонентами vue и js. В app.js добавить `require("./vendor/app-vue-includes");` и `require('./vendor/app-js-includes');`, а в admin.js добавить `require("./vendor/admin-vue-includes");` и `require('./vendor/admin-js-includes');`

Выгрузить конфигурацию, добавится конфигурация для галлереи и темизации. Если нужно для конкретной страницы сделать отдельную тему, то есть переменная `$customTheme`.

`php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=config`

`php artisan make:base-settings --all` - создает модели и шаблоны.

Есть базовые команды для пакетов что бы заполнить конфигурацию и модели.