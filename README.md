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

Для работы изображений настроить приложение:
    
    php artisan storage:link
    FILESYSTEM_DISK=public 
    
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


Чистка ключа кэша:
    
    php artisan cache:forget {key}

Чистка фильтра изображения:

    php artisan cache:forget "image-filters:{template}-{filename}"
    php artisan cache:forget "object-filters-original:{filename}"
    php artisan cache:forget "object-filters-content:{template}-{filename}"

Чистка всех фильтров:

    php artisan image-filters:clear

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
