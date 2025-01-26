<?php

namespace App\Observers;

use App\Models\Binnacle;
use Illuminate\Database\Eloquent\Model;

class GlobalModelObserver
{
    public function created(Model $model)
    {
        $module = class_basename($model) === 'User'
            ? ($model->getRoleNames()[0] === 'Client' ? 'Cliente' : 'Empleado')
            : class_basename($model);

        Binnacle::create([
            'module' => $module,
            'user' => auth()->user()->full_name,
            'rol' => auth()->user()->getRoleNames()[0],
            'action' => "Registro creado con el id: $model->id",
            'status' => 'successfull',
        ]);
    }

    public function updated(Model $model)
    {
        Binnacle::create([
            'module' => class_basename($model),
            'user' => auth()->user()->full_name,
            'rol' => auth()->user()->getRoleNames()[0],
            'action' => "Registro actualizado id: $model->id",
            'status' => 'successfull',
        ]);
    }

    public function deleting(Model $model)
    {
        Binnacle::create([
            'module' => class_basename($model),
            'user' => auth()->user()->full_name,
            'rol' => auth()->user()->getRoleNames()[0],
            'action' => "Registro borrado con el id: $model->id",
            'status' => 'warning',
        ]);
    }
}
