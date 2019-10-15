<?php


namespace PortedCheese\BaseSettings\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PortedCheese\BaseSettings\Http\Requests\SettingsStoreRequest;
use PortedCheese\BaseSettings\Http\Requests\SettingsUpdateRequest;
use PortedCheese\BaseSettings\Models\SiteConfig;

class SettingsController extends Controller
{
    /**
     * Список настроек.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("base-settings::admin.settings.index", [
            'settings' => SiteConfig::query()->orderBy("title")->get(),
            'user' => Auth::user(),
        ]);
    }

    /**
     * Добавить конфиг.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("base-settings::admin.settings.create");
    }

    /**
     * Сохранить конфиг.
     *
     * @param SettingsStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SettingsStoreRequest $request)
    {
        $config = SiteConfig::create($request->all());

        return redirect()
            ->route("admin.settings.edit", ['setting' => $config])
            ->with("success", "Создана конфигурация");
    }

    /**
     * Редактировать конфиг.
     *
     * @param SiteConfig $setting
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SiteConfig $setting)
    {
        return view("base-settings::admin.settings.edit", [
            'config' => $setting,
            'name' => $setting->name,
        ]);
    }

    /**
     * Обновить конфиг.
     *
     * @param SettingsUpdateRequest $request
     * @param SiteConfig $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingsUpdateRequest $request, SiteConfig $setting)
    {
        $userInput = $request->all();
        $data = [];
        foreach ($userInput as $key => $value) {
            if (strstr("$key", "data-") !== false) {
                $data[str_replace("data-", "", $key)] = $value;
            }
        }
        if (! empty($setting->data)) {
            foreach ($setting->data as $key => $value) {
                if (empty($data[$key])) {
                    $data[$key] = false;
                }
            }
        }
        $userInput['data'] = $data;
        $setting->update($userInput);

        return redirect()
            ->back()
            ->with("success", "Обновлено");
    }

    /**
     * Удалить конфиг.
     *
     * @param SiteConfig $setting
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(SiteConfig $setting)
    {
        if ($setting->package) {
            return redirect()
                ->back()
                ->with("danger", "Невозможно удалить");
        }

        $setting->delete();

        return redirect()
            ->route("admin.settings.index")
            ->with("success", "Конфигурация удалена");
    }

    /**
     * Обновить фавиконку.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFavicon(Request $request)
    {
        return redirect()
            ->back()
            ->with("danger", "Disabled");
//        Validator::make($request->all(), [
//            'favicon' => "required|file:mimes:ico",
//        ])->validate();
//        if ($request->has("favicon")) {
//            $request->file("favicon")->storeAs("", "favicon.ico", "public");
//        }
//        return redirect()
//            ->back()
//            ->with("Обновлено");
    }

    /**
     * Обновить токен.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateToken(User $user)
    {
        $token = $user->setBaseToken();
        return redirect()
            ->back()
            ->with("success", $token);
    }
}