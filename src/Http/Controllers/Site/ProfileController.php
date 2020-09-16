<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Site;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use PortedCheese\BaseSettings\Models\LoginLink;

class ProfileController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    public function __construct()
    {
        parent::__construct();

        if (Route::currentRouteName() !== "profile.auth.email-authenticate") {
            $this->middleware('auth');

            $this->middleware(function ($request, $next) {
                $userId = Auth::id();
                $this->user = User::find($userId);

                return $next($request);
            });
        }
    }

    /**
     * Профиль пользователя.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show() {
        return view('base-settings::profile.show', [
            'user' => $this->user,
            'routeName' => $this->routeName,
            'image' => $this->user->image,
        ]);
    }

    /**
     * Форма редактирования.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit() {
        return view('base-settings::profile.edit', [
            'user' => $this->user,
            'routeName' => $this->routeName,
        ]);
    }

    /**
     * Обновление пользователя.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        $this->updateValidator($request->all());

        $this->user->update($request->all());
        $this->user->uploadImage($request, "users");

        return redirect()
            ->route("profile.show")
            ->with('success', 'Успешно обновлено');
    }

    /**
     * Валидация обновления.
     *
     * @param $data
     */
    protected function updateValidator($data)
    {
        Validator::make($data, [
            "name" => ["required", "max:100"],
            "last_name" => ["nullable", "max:100"],
            "middle_name" => ["nullable", "max:100"],
            "email" => ["required", "email", "max:250", "unique:users,email,{$this->user->id}"],
            "password" => ["nullable", "string", "min:6", "confirmed"],
            "image" => ["nullable", "image"],
            "phone_number" => ["nullable", "max:100"],
        ], [], [
            "name" => "Имя",
            "last_name" => "Фамилия",
            "middle_name" => "Отчество",
            "email" => "E-mail",
            "password" => "Пароль",
            "image" => "Аватар",
            "phone_number" => "Номер телефона",
        ])->validate();
    }

    /**
     * Авторизация по ссылке.
     *
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function authenticateEmail(string $token)
    {
        $user = LoginLink::validFromToken($token);

        if ($user) {
            Auth::login($user);
            return redirect()
                ->route("login");
        }
        else {
            return redirect()
                ->route("login")
                ->with("danger", "Некорректный токен");
        }
    }
}
