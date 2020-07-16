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
                                   {--policies : Export and create rules}
                                   {--only-default : Create default rules}
                                   {--config : Make config}
                                   {--scss : Export scss files}
                                   {--vue : Export vue files}
                                   {--js : Export js files}
    
### Middleware

- management: доступ в административную часть сайта.
- super: пропускает админа
- editor: пропускает редактора

### Gates

- пользователи с ролью admin имею доступ ко всему
- site-management: право доступа к админке.
- settings-management: открыто для админа

### Команды и функции

Функция для работы с датами: 
    
    datehelper()
    {->forFilter(date, date to condition = false)}
    {->changeTz(date)}
    {->format(date, format = "d.m.Y H:i")
    
Генерация ссылки на вход:
    
    php artisan generate:login-link {email} {--send=} {--get}
    
### Components

universal-priority:

    <universal-priority
        :elements="{{ json_encode([['name' => "name", "id" => "id"(, "url" => "url")], [..], [..]]) }}"
        url="{{ route("admin.vue.priority", ['table' => "table_name", "field" => "field_name"]) }}">
    </universal-priority>

### Includes

`@googleCaptcha2` - Google ReCaptcha для форм, `google_captcha` - правило валидации

`@hiddenCaptcha` - Скрытая капча, `hidden_captcha` - валидация

Редактирование галереи:

    @eGall(["id" => Auth::user()->id, "model" => "user", "noCover" => false])

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
    
    v1.6.0:
        - Изменено подключение стилей и скриптов на mix() вместо asset().
        - В app добавлен шаблон base-settings::layouts.scripts, для подключения метрики и прочего.
        - Из paper и fonts убран fontawesome.
        - Добвлена новая тема SBAdmin2
    Обновление:
        - В webpack добавить, если его нет .version()
        - Установить fontawesome:
            - npm install @fortawesome/fontawesome-free
            - В app.scss и admin.scss дописать:
                @import "~@fortawesome/fontawesome-free/scss/fontawesome";
                @import "~@fortawesome/fontawesome-free/scss/regular";
                @import "~@fortawesome/fontawesome-free/scss/solid";
                @import "~@fortawesome/fontawesome-free/scss/brands";
            - В webpack дописать:
                .copy("node_modules/@fortawesome/fontawesome-free/webfonts", "public/webfonts")
            - В gitignore дописать:
                /public/webfonts
        
    v1.5.2:
        - испралена функция update в base_config
        
    v1.5.1:
        - доблена функция base_config
        
    v1.5.0:
        - Добавлены новые traits: ShouldGallery, ShouldImage, ShouldSlug
        - Теперь не нужно прописывать вызов методов в boot
    Обновление:
        - Можно заменить HasImage и HasSlug на новые
        
    v1.4.29:
        - CkEditor заменен на TinyMCE
    Обновление:
        - npm install tinymce
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
        - Добавить в webpack:
            .copy("node_modules/tinymce/skins", "public/js/skins")
            .copy("node_modules/tinymce/icons", "public/js/icons")
    
    v1.4.27:
        - В шаблон content для ссылок добавлен yield "links-cover-class"
    
    v1.4.25:
        - В шаблон content добавлена секция contents
    
    v1.4.24:
        - Если через админку не заполнить имя пользователя, то автоматически возмется начало email
        - В профиле Имя теперь обязательно для заполнения
        
    v1.4.22:
        - Изменен пользователь. UserStoreRequest не используется. UserUpdateRequest не используется.
        - Роуты вынесены в пакет.
        - Изменен путь сохранения изображений.
    Обновление:
        - Проверить не переписан ли UserStoreRequest и UserUpdateRequest
        - Удалить роуты пользователя из admin.php
        - В imagecache добавить 'storage/users',
        
    v1.4.21:
        - Изменено подключение компоментов.
        
    v1.4.18:
        - В app добавлен шаблон favicon
        - Шрифты в app вынесены в отдельный шаблон fonts
    
    v1.4.17:
        - Переделаны шаблоны layouts:
            - В app:
                - у main убран класс py-4, и заменен на main-section;
                - к тэгу footer добавлен класс footer-section
            - В content:
                - header-title вынесен в отдельный row;
                - добавлен класс header-title-cover и возможность его заменить через header-title-cover-class;
                - добавлен raw-header-title, в этом случае заголовок можно сделать как угодно внутри row;
                - contetn обернут в отдельный row, к которому добавляется класс content-cover с возможностью изменить через content-cover-class
                - links вынесены в отдельный row
            - В main-section:
                - Breadcrumbs вынесены в отдельный файл;
                - messages обернуты в row и col-12;
                - у content и sidebar убраны обертки row
                - к aside добавлен класс sidebar-section
                - к section в которой content добавлен класс content-section
            
    
    v1.4.16:
        - В команду добавдена опция --scss, создает файл инклудов стилей
    Обновеление:
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
        - php artisan make:base-settings --scss
        - В app.scss заменить импорт base на app-includes

    v1.4.15:
        - В команду добалена опция --only-default, вместе с policies заполняет только значения по умолчанию.
    
    v1.4.13:
        - Добавлен trait для комманды, который генерирует модели политик для сайта
    
    v1.4.12:
        - Добавлен include @eGall для редактирования галереии
    
    v1.4.10:
        - Добавлен кэш на права доступа
        
    v1.4.5:
        - Поправлена генерация политик, тперь создается папка если ее еще нет.
    
    v1.4.3:
        - Новая модель RoleRule, которая задает настройку прав доступа для роли при помощи Policy.
        - Два новых контроллера для создания новых ролей и редактирования их прав доступа.
        - Добавлены новые middleware:
            - management: доступ в административную часть сайта.
            - super: пропускает админа
            - editor: пропускает редактора
        - В базовую команду добавлен новый параметр --polices, копирует политику и создает модель RoleRule
            - Если у политики будет метод defaultRules, то попробует задать права, метод долженр вернуть число (сумма степеней двойки)
        - доблены Gates:
            - site-management: право доступа к админке.
            - пропуск админов во все
            - settings-management: открыто для админа
        - Права доступа на редактирование пользователей
    Обновление:
        - php artisan migrate
        - php artisan make:base-settings --models --controllers --policies
        - изменить в роутере для админки ограничение по ролям и поставить management
        - добавить ограничения на редактирование пользователей (поставить "editor" middleware)
    
    v1.4.2:
        - Изменение css в paper layout на admin.css
        - в webpack.mix.js добавить .sass('resources/sass/admin.scss', 'public/css')