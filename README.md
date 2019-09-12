# Base settings

Набор классов, представлений и компонентов для разворачивания базового сайта.

### Установка

Выгрузить компоненты VueJs, стили и скрипт.

`php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force`

Комманда make так же предложит наполнить файл компонентами vue. В app.js добавить `import "./vendor/app-vue-includes";` и в admin.js добавить `import "./vendor/admin-vue-includes";`

Выгрузить конфигурацию, добавится конфигурация для галлереи и темизации. Если нужно для конкретной страницы сделать отдельную тему, то есть переменная `$customTheme`. Еще добавлен конфиг для сайта, который связан с фасадом `siteconf`, через него можно программно получить значение этого конфига и изменить его.

`php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=config`

`php artisan make:base-settings` - создает модели и шаблоны. Флаг `--views` говорит выгрузить только шаблоны, флаг `--force` говорит заменить шаблоны без подтверждения. На модели всегда спрашивается разрешение на замену.

Есть базовые команды для пакетов что бы заполнить конфигурацию и модели, а так же команда для перезаписи контроллеров и роутов.