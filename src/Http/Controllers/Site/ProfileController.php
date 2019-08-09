<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
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
            'sex' => $this->user->getSexList(),
            'routeName' => $this->routeName,
            'image' => $this->user->avatar,
        ]);
    }


    public function update(Request $request) {
        $userInput = $request->all();
        $rules = [
            'login' => "required|unique:users,login,{$this->user->id}|min:2",
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
}
