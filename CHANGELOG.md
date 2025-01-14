### Versions
    v5.0.5: tiny init fix
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force

    v5.0.4: messages btn-close
    v5.0.3: image-filters: support cloud

    v5.0.0-5.0.3: bootstrap 5
        - new filters: xl-grid-6, xxl-grid-6, xl-grid-4, xxl-grid-4, xl-grid-3, xxl-grid-3
        - change app-base.js, admin-base.js
        - change admin views
        - change gallery component: justify-content-start
        - change layouts.messages, layouts.user-menu, layouts.sb-admin
        
    Обновление:
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
        - php artisan image-filters:clear, php artisan config:clear, php artisan cache:clear

    v4.2.2-4.2.4: tint init change
       - add to webpack (tiny ^7.0): .copy("node_modules/tinymce/models", "public/js/models")
       - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    v4.2.1: .custrom-control-label > .badge
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
    v4.2.0: Laravel 10
    v4.1.4-4.1.6: add widen-logo filter,  add image-filters:clear command, add d-hover & d-hover-relative style
        - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=public --force
            
    v4.1.1 - v4.1.3: Remove Imagecache, add Small,Medium,Large filters :
        - composer remove intervention/imagecache   
        - check imagecache.config  (remove Small, Medium & Large filters)

    v4.1.0: Add ImageFilter (instead of intervention/imagecache) :
        - php artisan migrate
        - php artisan make:base-settings --models (y - для создания ImageFilter model)
        - php artisan make:base-settings --controllers (y - для создания Site/FilterController)

        Для конфигурации url фильтра, времени жизни кэша, шаблонов и путей:
            - php artisan vendor:publish --provider="PortedCheese\BaseSettings\BaseSettingsServiceProvider" --tag=config
        Для совместимости с Imagecache-фильтрами других пакетов добавить в провайдер проекта:
            app()->config['image-filter.templates'] = array_merge(app()->config['imagecache.templates'],app()->config['image-filter.templates']);
            app()->config['image-filter.paths'] = array_merge(app()->config['imagecache.paths'], app()->config['image-filter.paths']); 
        
        Обновлены @pic @img @picLazy @imgLazy (change route "imagecache" to "image-filter")

    v4.0.3: ShouldGallery new methods
    v4.0.2: ShouldImage new methods
    v4.0.1: Add RedirectController
        - php artisan make:base-settings --controllers (y - для создания Site/RedirectController)
    v4.0.0:  Laravel 9 & Schema::defaultStringLength(255)
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