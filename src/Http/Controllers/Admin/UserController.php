<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Admin;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    const PAGER = 10;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->query;
        $users = User::query();
        if ($query->get('login')) {
            $login = trim($query->get('login'));
            $users->where('login', 'LIKE', "%$login%");
        }
        if ($query->get('email')) {
            $email = trim($query->get('email'));
            $users->where('email', 'LIKE', "%$email%");
        }
        if ($query->get('surname')) {
            $surname = trim($query->get('surname'));
            $users->where('surname', 'LIKE', "%$surname%");
        }
        if ($query->get('verified', 'all') != 'all') {
            $verified = $query->get('verified');
            if ($verified) {
                $users->whereNotNull('email_verified_at');
            }
            else {
                $users->whereNull('email_verified_at');
            }
        }
        $users->orderBy('created_at', 'desc');
        return view('base-settings::admin.user.index', [
            'users' => $users->paginate(env("USER_ADMIN_PAGER", self::PAGER))->appends($request->input()),
            'query' => $query,
            'per' => self::PAGER,
            'page' => $query->get('page', 1) - 1
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('base-settings::admin.user.create', [
            'sex' => Auth::user()->getSexList(),
            'roles' => Role::getForAdmin(),
        ]);
    }

    /**
     * Сохранение нового пользователя.
     *
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        $userInput = $request->all();
        $user = User::create($userInput);
        $roles = [];
        foreach ($userInput as $input => $name) {
            if (strstr($input, 'check') === FALSE) {
                continue;
            }
            $roles[] = $name;
        }
        $user->setRoles($roles);
        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь добавлен');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('base-settings::admin.user.edit', [
            'user' => $user,
            'sex' => $user->getSexList(),
            'roles' => Role::getForAdmin(),
            'auth' => Auth::user(),
            'image' => $user->avatar,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $userInput = $request->all();
        $user->update($userInput);
        $user->uploadAvatar($request);
        $roles = [];
        foreach ($userInput as $input => $name) {
            if (strstr($input, 'check') === FALSE) {
                continue;
            }
            $roles[] = $name;
        }
        $user->setRoles($roles);
        return redirect()->back()
            ->with('success', 'Успешно обновлено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if (Auth::user()->id == $user->id) {
            return redirect()->back()->with('danger', 'Невозможно удалить себя!');
        }
        if ($user->hasRole('admin')) {
            return redirect()->back()->with('danger', 'Невозможно удалить админа!');
        }
        $user->delete();
        return redirect()->back()
            ->with('success', 'Пользователь удален!');
    }
}
