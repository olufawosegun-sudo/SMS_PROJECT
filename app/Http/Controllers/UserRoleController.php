<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRoleController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $users = User::where('school_id', $school->id)
            ->with('role')
            ->orderBy('created_at', 'desc')
            ->get();
        $roles = Role::all();

        $usersByRole = $users->groupBy(fn($u) => $u->role->name ?? 'Unknown');

        return view('users-roles.index', compact('users', 'roles', 'usersByRole', 'school'));
    }
}
