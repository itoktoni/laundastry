<?php

namespace App\Http\Controllers\Core;

use App\Dao\Models\Core\User;
use App\Dao\Models\Customer;
use App\Facades\Model\RoleModel;
use App\Facades\Model\UserModel;
use App\Http\Requests\Core\LoginRequest;
use App\Http\Requests\Core\UserRequest;
use App\Services\Master\CreateService;
use App\Services\Master\SingleService;
use App\Services\Master\UpdateService;
use Illuminate\Support\Facades\Hash;
use Plugins\Notes;
use Plugins\Response;

class UserController extends MasterController
{
    public function __construct(User $model, SingleService $service)
    {
        $this->model = $model::getModel();
        self::$service = self::$service ?? $service;
        self::$is_core = true;
    }

    protected function share($data = [])
    {
        $roles = RoleModel::getOptions();
        $customer = Customer::getOptions();
        $selected = [];

        $view = [
            'roles' => $roles,
            'customer' => $customer,
            'selected' => $selected,
            'model' => $this->model
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function postCreate(UserRequest $request, CreateService $service)
    {
        $data = $service->save($this->model, $request);
        $data['data']->has_customer()->sync($request->get('customer'));

        return Response::redirectBack($data);
    }

    public function getUpdate($code)
    {
        $model = $this->get($code, ['has_customer']);
        $selected = $model->has_customer->pluck(Customer::field_primary()) ?? [];

        return $this->views($this->template(core : self::$is_core), $this->share([
            'model' => $this->get($code),
            'selected' => $selected
        ]));
    }

    public function postUpdate($code, UserRequest $request, UpdateService $service)
    {
        $data = $service->update($this->model, $request, $code);
        $data['data']->has_customer()->sync($request->get('customer'));

        return Response::redirectBack($data);
    }

    public function getTable()
    {
        $data = $this->getData();

        return moduleView(modulePathTable('user', true), [
            'data' => $data,
            'fields' => $this->model::getModel()->getShowField(),
        ]);
    }

    public function changePassword()
    {
        if (request()->method() == 'POST') {

            UserModel::find(auth()->user()->id)->update([
                'password' => bcrypt(request()->get('password')),
            ]);

            return redirect()->route('home');
        }

        return view('auth.change_password')->with($this->share());
    }

    public function postLoginApi(LoginRequest $request)
    {
        $user = UserModel::where('username', $request->username)->first();

        if (! Hash::check($request->password, $user->password)) {
            return Notes::error([
                'password' => 'Password Tidak Di temukan',
            ], 'Login Gagal');
        }

        $token = $user->createToken($user->name);
        $string_token = $token->plainTextToken;
        $user->api_token = $string_token;
        $user->save();

        return Notes::token($user->toArray());
    }

    public function getExportJob()
    {
        return moduleView(modulePathForm(path: true), $this->share());
    }

    public function getProfile()
    {
        if(request()->header('authorization'))
        {
            return auth()->user();
        }

        return moduleView(modulePathForm(name: 'form', path: 'core.profile'), $this->share([
            'model' => auth()->user(),
        ]));
    }

    public function updateProfile(UserRequest $request, UpdateService $service)
    {
        $data = $service->update($this->model, $request, auth()->id());

        return Response::redirectBack($data);
    }
}
