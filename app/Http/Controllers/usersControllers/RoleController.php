<?php

namespace App\Http\Controllers\UsersControllers;

use App\Http\Controllers\Controller;

use App\Models\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function index()
    {

        return Role::select('id', 'nom')->get();
    }

    public function store(Request $request)
    {
        if ($request->role['nom']) {
            Role::create([
                'nom' => $request->role['nom']
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if ($request->role['nom']) {
            $role->update([
                'nom' => $request->role['nom']
            ]);
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
        }
    }
}
