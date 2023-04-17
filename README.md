# Base settings

Набор классов, представлений и компонентов для разворачивания базового сайта.

Есть базовые команды для пакетов что бы заполнить конфигурацию и модели.

## Установка
    php artisan ui vue --auth
    npm install && npm run dev
    php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=config
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
                                       
В контроллер `App\Http\Controllers\Controller` добавить конструктор:

    public function __construct()
    {
        $this->routeName = Route::currentRouteName();
    }

Настроить `intervention/imagecache` что бы адрес был `imagecahce`
Для работы изображений настроить приложение:
    
    php artisan storage:link
    FILESYSTEM_DRIVER=public 
    
### Middleware

- role: доступ по наличию роли
- management: доступ в административную часть сайта.
- super: пропускает админа
- editor: пропускает редактора

### Gates

- пользователи с ролью admin имею доступ ко всему
- site-management: право доступа к админке. (`App\Policies\BasePolicy@siteManagement`)
- settings-management: открыто для админа. (`App\Policies\BasePolicy@settingsManagement`)

### Команды и функции

Функция для работы с датами: 
    
    datehelper()
        {->forFilter(date, date to condition = false)}
        {->changeTz(date)}
        {->format(date, format = "d.m.Y H:i")
        
Функция для работы с конфигурацией сайта:
    
    base-config()
        @method static get(string $name, $value = "", $default = null)
        @method static create(string $name, array $data, array $info, $force = false)
        @method static update(string $name, string $var, $value)
    
Генерация ссылки на вход:
    
    php artisan generate:login-link {email} {--send=} {--get}
    
### Components

universal-priority:

    <universal-priority
        :elements="{{ json_encode([['name' => "name", "id" => "id"(, "url" => "url")], [..], [..]]) }}"
        url="{{ route("admin.vue.priority", ['table' => "table_name", "field" => "field_name"]) }}">
    </universal-priority>
    
confirm-form:
    
    <confirm-form :id="'{{ "delete-form-{$model->id}" }}'">
        <template>
            <form action="{{ route('admin.model.destroy', ['model' => $model]) }}"
                  id="delete-form-{{ $model->id }}"
                  class="btn-group"
                  method="post">
                @csrf
                @method("delete")
            </form>
        </template>
    </confirm-form>
    Параметры:
        - title: Вы уверены?
        - text: Это действие будет невозможно отменить!
        - confirm-text: Да, удалить!
        - cancel-text: Отмена

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

Вывод тега `picture` Lazy:

    @picLazy([
        "image" => (object) ["file_name" => "example.jpg", "name" => "example"],
        "template" => "small",
        "grid" => [
            "example-grid" => 768,
        ],
        "imgClass" => "example-class",
    ])

Вывод изображения Lazy с `lightbox` :

    @imgLazy([
        "image" => (object) ["file_name" => "example.jpg", "name" => "example", "id" => "unique"], (id требуется если lightbox не указан)
        "template" => "small",
        "lightbox" => "lightGroupExample",
        "imgClass" => "example-class",
        "grid" => [
            "example-grid" => 768,
        ],
    ])
Вывод галереии Lazy с `lightbox` :

    @imagesLazy([
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
    v3.1.4: Change Init TinyMCE (fix Tiny modal dialog)
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    v3.1.3: Detect IE fix
    v3.1.2: TinyMCE colors:
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    v3.1.0: Lazy images support, $detectIe:
        - npm install lazysizes
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
        - use: @picLazy, @imgLazy, @imageslazy
    v3.0.6: TinyMCE files upload: false
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    v3.0.5: TinyMCE init: add btn
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    v3.0.4: Sweetalert2-neutral
        - npm uninstall sweetalert2
        - npm install sweetalert2-neutral
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    v3.0.3: unset vendorName variable
    v3.0.2: add Bootstrap paginator to provider
    v3.0.1: add vendorName to commands
    v.3.0.0: 
        Laravel 8
    v.2.1.2:
        В панели управления добавлены ограничения для пользвоателей, не имеющих роли admin   
        - добавлены константы для default-ролей: admin и editor 
        - новый метод  getSuperId() модели Role - для получения id роли администратора
        - новый метод getNoSuperUsers() модели User - для получения пользователей без прав администратора
        - обновлен метод setRoles() модели User 
        (ограничения для не адмнистраторов при обновлении ролей пользователя)    
        - Обновлен контроллер UserController: 
            -- в методах index,edit: пользователям без прав администратора не отображаются пользователи-администраторы
            -- в методах store, update: пользователь без прав админитсратора не может назначать и снимать роль администратора
            -- новая функция isAdminRolesInput - проверяет переданные роли на наличие роли администратора
        
        
    v2.0.0:
        - У пользователя убран шаблон layout
        - К пользователю добалвен номер телефона
        - Изменен внешний вид личного кабинета
        - Добавлен шаблон для svg
        - Убрана дата frontend из настроек
        - Изменен метод обновления пользователя
    Обновление:
        - php artisan migrate
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
        - php artisan make:base-settings --scss (profile-page yes)
        - Если изменена форма редактирования пользователя, добавить метод PUT
        
    v1.7.15:
        - В base.scss добавлен класс upper-label
    Обновление:
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
        
    v1.7.14:
        - В base.scss изменен цвет noUi slider
    Обновление:
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
        
    v1.7.13:
        - В main-section.blade.php добавлена секция header-upper-title и raw-header-upper-title
        
    v1.7.12:
        - В app-base.js изменены настройки noUiSlider
    
    v1.7.7:
        - В app.blade.php добавлен stack svg
    
    v1.7.3:
        - Добален класс исключения PreventDeleteException
        
    v1.7.0:
        - Изменены зависимости
        - Доблены базовые адреса для управления
        - Роуты разбиты по файлам, можно переопределить файл
    Обновление:
        - Удалить роуты: admin; admin.logs; profile.*
        
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