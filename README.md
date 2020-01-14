# Base settings

Набор классов, представлений и компонентов для разворачивания базового сайта.

Есть базовые команды для пакетов что бы заполнить конфигурацию и модели.

## Установка

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

`@googleCaptcha2` - Google ReCaptcha для форм, `google_captcha` - правило валидации

`@hiddenCaptcha` - Скрытая капча, `hidden_captcha` - валидация

Функция для работы с датами: 
    
    datehelper()
    {->forFilter(date, date to condition = false)}
    {->changeTz(date)}
    {->format(date, format = "d.m.Y H:i")
    
### Includes
Вывод тега `picture`:

    @pic([
        "image" => (object) ["file_name" => "example.jpg", "name" => "example"],
        "template" => "small",
        "grid" => [
            "example-grid" => 768,
        ],
        "imgClass" => "example-class",
    ])

Вывод изображения с `lightbox`:

    @img([
        "image" => (object) ["file_name" => "example.jpg", "name" => "example", "id" => "unique"], (id требуется если lightbox не указан)
        "template" => "small",
        "lightbox" => "lightGroupExample",
        "imgClass" => "example-class",
        "grid" => [
            "example-grid" => 768,
        ],
    ])
    
Вывод галереии с `lightbox`:
    
    @images([
        "gallery" => [(object) ..., (object) ...],
        "lightbox" => "lightExampleGroup",
        "template" => "sm-grid-6",
        "grid" => [
            "lg-grid-3" => 992,
            "md-grid-6" => 768,
        ],
        "imgClass" => "img-fluid",
    ])
    
### Versions

    v1.4.2:
    - Изменение css в paper layout на admin.css