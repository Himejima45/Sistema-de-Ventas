<?php

namespace App\Observers;

use App\Models\Binnacle;
use Illuminate\Database\Eloquent\Model;

class GlobalModelObserver
{

    public function created(Model $model)
    {
        $module = class_basename($model) === 'User'
            ? (
                $model?->getRoleNames() !== null
                ? 'Sistema'
                : ($model?->getRoleNames()[0] === 'Client' ? 'Cliente' : 'Empleado')
            )
            : class_basename($model);

        Binnacle::create([
            'module' => $module,
            'user' => auth()->user()->full_name ?? 'Sistema',
            'rol' => auth()->user()?->getRoleNames() !== null ? auth()->user()?->getRoleNames()[0] ?? 'Sistema' : 'Sistema',
            'action' => "Registro creado con el id: $model->id",
            'status' => 'successfull',
        ]);
    }

    public function updated(Model $model)
    {
        Binnacle::create([
            'module' => class_basename($model),
            'user' => auth()->user()->full_name ?? 'Sistema',
            'rol' => auth()->user()?->getRoleNames()[0] ?? 'Sistema',
            'action' => "Registro actualizado id: $model->id",
            'status' => 'successfull',
        ]);
    }

    public function deleting(Model $model)
    {
        Binnacle::create([
            'module' => class_basename($model),
            'user' => auth()->user()->full_name ?? 'Sistema',
            'rol' => auth()->user()?->getRoleNames()[0] ?? 'Sistema',
            'action' => "Registro borrado con el id: $model->id",
            'status' => 'warning',
        ]);
    }
}
