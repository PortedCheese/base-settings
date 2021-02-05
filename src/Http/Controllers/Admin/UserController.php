<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Admin;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Output\BufferedOutput;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(User::class, "user");
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Администраторов видит только администратор
        if (! Auth::user()->hasRole(Role::SUPER))
            $collection = User::getNoSuperUsers();
        else
            $collection = User::query();
        $this->searchQuery($collection, $request);
        $perPage = siteconf()->get("base-settings", "userAdminPager");
        return view('base-settings::admin.user.index', [
            'users' => $collection
                ->paginate($perPage)
                ->appends($request->input()),
            'query' => $request->query,
            'per' => $perPage,
            'page' => $request->query->get('page', 1) - 1
        ]);
    }

    /**
     * Поиск.
     *
     * @param $collection
     * @param Request $request
     */
    protected function searchQuery($collection, Request $request)
    {
        $query = $request->query;
        if ($query->get('email')) {
            $email = trim($query->get('email'));
            $collection->where('email', 'LIKE', "%$email%");
        }
        if ($query->get('full_name')) {
            $title = $query->get('full_name');
            $collection->where(function ($query) use ($title) {
                $query->where("name", "like", "%$title%")
                    ->orWhere("last_name", "like", "%$title%")
                    ->orWhere("middle_name", "like", "%$title%");
            });
        }
        if ($query->get('verified', 'all') != 'all') {
            $verified = $query->get('verified');
            if ($verified) {
                $collection->whereNotNull('email_verified_at');
            }
            else {
                $collection->whereNull('email_verified_at');
            }
        }
        $collection->orderBy('created_at', 'desc');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('base-settings::admin.user.create', [
            'roles' => Role::getForAdmin(),
        ]);
    }

    /**
     * Сохранение нового пользователя.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->storeValidator($request->all());

        //задать администратора может только администратор
        if (!Auth::user()->hasRole(Role::SUPER)) {
            if ($this->isAdminRolesInput($request))
                return redirect()->route('admin.users.index')
                    ->with('danger', 'Пользователь не может быть добавлен');
        }

        $user = User::create($request->all());
        $user->uploadImage($request, "users");

        $user->roles()->sync($request->get("roles", []));
        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь добавлен');
    }
    /**
     * Проверяем, указана ли роль Адмниситратора в форме
     *
     * @param Request $request
     * @return bool
     */

    protected function isAdminRolesInput (Request $request) {

        $inputRoles = $request->input("roles");
        foreach ($inputRoles as $inputRole) {
            $adminRoleId = Role::getSuperId();
            if ($inputRole ==  $adminRoleId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Валидация создания.
     *
     * @param $data
     */
    protected function storeValidator($data)
    {
        Validator::make($data, [
            "email" => ["required", "email", "unique:users,email"],
            "password" => ["required", "string", "min:6", "confirmed"],
        ])->validate();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //администраторов может открывать для редактирования только администратор
        if (!Auth::user()->hasRole(Role::SUPER) && ($user->hasRole(Role::SUPER)))
        {
            return redirect()->route('admin.users.index');
        }
        return view('base-settings::admin.user.edit', [
            'user' => $user,
            'roles' => Role::getForAdmin(),
            'auth' => Auth::user(),
            'image' => $user->image,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $this->updateValidator($request->all(), $user);

        //добавить роль администратора может только администратор
        if (!Auth::user()->hasRole(Role::SUPER)) {
            if ($this->isAdminRolesInput($request))
                return redirect()->route('admin.users.index')
                    ->with('danger', 'Пользователь не может быть обновлен');
        }

        $user->update($request->all());
        $user->uploadImage($request, "users");
        $user->setRoles($request->get("roles", []));
        return redirect()->back()
            ->with('success', 'Успешно обновлено');
    }

    /**
     * Валидация обновления.
     *
     * @param $data
     * @param User $user
     */
    protected function updateValidator($data, User $user)
    {
        $id = $user->id;
        Validator::make($data, [
            "email" => ["required", "email", "unique:users,email,{$id}"],
        ], [], [
            "email" => "E-mail",
        ])->validate();
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
        if ($user->hasRole(Role::SUPER)) {
            return redirect()->back()->with('danger', 'Невозможно удалить админа!');
        }
        $user->delete();
        return redirect()->back()
            ->with('success', 'Пользователь удален!');
    }

    /**
     * Получить ссылку на вход.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLoginLink(User $user)
    {
        $output = new BufferedOutput;

        Artisan::call("generate:login-link", [
            'email' => $user->email,
            "--get" => true,
        ], $output);

        return redirect()
            ->back()
            ->with("success", $output->fetch());
    }

    /**
     * Отправить ссылку на вход.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendLoginLink(User $user)
    {
        $output = new BufferedOutput;

        Artisan::call("generate:login-link", [
            'email' => $user->email,
        ], $output);

        return redirect()
            ->back()
            ->with("success", $output->fetch());
    }

    /**
     * Отправить ссылку на вход для текущего пользователя на конкретный e-mail.
     *
     * @param string $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLoginLinkForCurrentUserTo(string $email)
    {
        $output = new BufferedOutput;

        Artisan::call("generate:login-link", [
            'email' => Auth::user()->email,
            '--send' => $email,
        ], $output);

        return response()
            ->json("Ссылка отправлена");
    }
}
