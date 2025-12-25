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

     public function template($file = null, $folder = null, $core = false)
    {
        // Get the class name (e.g., UserController)
        $className = class_basename(get_class($this));

        // Remove 'Controller' suffix and convert to lowercase
        $module = strtolower(str_replace('Controller', '', $className));

        // Get the method name (e.g., getCreate)
        $method = debug_backtrace()[1]['function'];

        // Remove 'get' or 'post' prefix and convert to lowercase
        $action = strtolower(preg_replace('/^(get|post)/', '', $method));

        if(in_array($action, ['update', 'create']))
        {
            $action = 'form';
        }

        if ($file)
        {
            $action = $file;
        }

        if($file === true)
        {
            return $module;
        }

        if($folder)
        {
            $module = $folder;
        }

        $path = 'pages.';

        if($core)
        {
            $path = 'core.';
        }

        return $path.$module.'.'.$action;
    }
}
