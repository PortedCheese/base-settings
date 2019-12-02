# Base settings

Набор классов, представлений и компонентов для разворачивания базового сайта.

### Установка

Выгрузить компоненты VueJs, стили и скрипт.

    php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force

Выгрузить конфигурацию, добавится конфигурация для галлереи и темизации. Если нужно для конкретной страницы сделать отдельную тему, то есть переменная `$customTheme`.

    php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=config

Комманда make так же предложит наполнить файлы компонентами vue и js. В app.js добавить `require("./vendor/app-vue-includes");` и `require('./vendor/app-js-includes');`, а в admin.js добавить `require("./vendor/admin-vue-includes");` и `require('./vendor/admin-js-includes');`

    php artisan make:base-settings {--all : Run all}
                                   {--views : Scaffold views}
                                   {--force : Overwrite existing views by default}
                                   {--models : Export models}
                                   {--filters : Export filters}
                                   {--controllers : Export controllers}
                                   {--config : Make config}
                                   {--vue : Export vue files}
                                   {--js : Export js files}

Есть базовые команды для пакетов что бы заполнить конфигурацию и модели.

`@googleCaptcha2` - Google ReCaptcha для форм, `google_captcha` - правило валидации

`@hiddenCaptcha` - Скрытая капча, `hidden_captcha` - валидация

Функция для работы с датами: 
    
    datehelper()
    {->forFilter(date, date to condition = false)}
    {->changeTz(date)}
    {->format(date, format = "d.m.Y H:i")