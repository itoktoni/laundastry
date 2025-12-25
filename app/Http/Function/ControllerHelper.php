<?php

namespace App\Http\Function;

trait ControllerHelper
{
    protected function views(string $view, array $data = [], int $status = 200)
    {
        if (request()->expectsJson()) {
            return response()->json($data, $status);
        }

        return view($view, $this->share($data));
    }

    public function module($function = null)
    {
        // Get the class name (e.g., UserController)
        $className = class_basename(get_class($this));

        // Remove 'Controller' suffix and convert to lowercase
        $module = strtolower(str_replace('Controller', '', $className));

        // Get the method name (e.g., getCreate)
        $method = debug_backtrace()[1]['function'];

        // Remove 'get' or 'post' prefix and convert to lowercase
        $action = strtolower(preg_replace('/^(get|post)/', '', $method));

        if ($function)
        {
            $action = $function;
        }

        if($function === true)
        {
            return $module;
        }

        return $module.'.'.$action;
    }
}
