<?php

namespace App\Http\Controllers\Core;

use App\Dao\Enums\Core\LevelType;
use App\Facades\Model\GroupModel;
use App\Facades\Model\RoleModel;
use App\Http\Requests\Core\RoleRequest;
use App\Services\Core\UpdateRoleService;
use App\Services\Master\CreateService;
use App\Services\Master\SingleService;
use Plugins\Response;

class RolesController extends MasterController
{
    public function __construct(RoleModel $model, SingleService $service)
    {
        $this->model = $model::getModel();
        self::$service = self::$service ?? $service;
        self::$is_core = true;
    }

    protected function share($data = [])
    {
        $group = GroupModel::getOptions();
        $level = LevelType::getOptions();

        $view = [
            'group' => $group,
            'level' => $level,
            'model' => $this->model
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function postCreate(RoleRequest $request, CreateService $service)
    {
        $data = $service->save($this->model, $request);

        return Response::redirectBack($data);
    }

    public function postUpdate($code, RoleRequest $request, UpdateRoleService $service)
    {
        dd($request);
        $data = $service->update($this->model, $request, $code);

        return Response::redirectBack($data);
    }

    public function getUpdate($code)
    {
        $data = $this->get($code, ['has_group']);
        $selected = $data->has_group->pluck('system_group_code') ?? [];

        return moduleView(modulePathForm(path: self::$is_core), $this->share([
            'model' => $data,
            'selected' => $selected,
        ]));
    }
}
