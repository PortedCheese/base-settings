<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Site;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use PortedCheese\BaseSettings\Models\LoginLink;

class ProfileController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();

        if (Route::currentRouteName() !== "profile.auth.email-authenticate") {
            $this->middleware('auth');

            $this->middleware(function ($request, $next) {
                $userId = Auth::user()->getAuthIdentifier();
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
            'image' => $this->user->avatar,
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
            'image' => $this->user->avatar,
        ]);
    }


    public function update(Request $request) {
        // TODO: move to request file.
        $userInput = $request->all();
        $rules = [
            'email' => "required|email|unique:users,email,{$this->user->id}",
            'password' => 'string|min:6|confirmed',
        ];
        if (!$request->filled('password')) {
            unset($rules['password']);
            unset($userInput['password']);
        }
        $request->validate($rules);
        $this->user->update($userInput);
        $this->user->uploadAvatar($request);

        return redirect()->back()->with('success', 'Успешно обновлено');
    }

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
