<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username',
            'dni'      => 'nullable|string|digits:8|unique:users,dni',
            'cmp'      => 'required_if:role,medico|nullable|string|max:10|unique:users,cmp',
            'rne'      => 'nullable|string|max:10|unique:users,rne',
            'role'     => 'required|in:superadmin,admin,medico,enfermera',
            'state'    => 'required|in:ACTIVO,INACTIVO',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'dni.digits' => 'El número de DNI debe contener exactamente 8 dígitos.',
            'cmp.required_if' => 'El CMP es obligatorio para registrar personal médico.',
            'unique'     => 'El parámetro ingresado en :attribute ya se encuentra registrado en el sistema clínico.'
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Personal registrado correctamente en la plataforma.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'dni'      => ['nullable', 'string', 'digits:8', Rule::unique('users')->ignore($user->id)],
            'cmp'      => ['required_if:role,medico', 'nullable', 'string', 'max:10', Rule::unique('users')->ignore($user->id)],
            'rne'      => ['nullable', 'string', 'max:10', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:superadmin,admin,medico,enfermera',
            'state'    => 'required|in:ACTIVO,INACTIVO',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6', 
        ], [
            'dni.digits' => 'El número de DNI debe contener exactamente 8 dígitos.',
            'cmp.required_if' => 'El CMP es obligatorio para registrar personal médico.',
            'unique'     => 'Los datos de este :attribute ya pertenecen a otro miembro del personal.'
        ]);

        if (auth()->id() === $user->id && $request->state === 'INACTIVO') {
            return redirect()->route('users.index')->with('error', 'Operación cancelada. No puedes cambiar tu propio estado a INACTIVO.');
        }

        // Si el password no viene, lo quitamos para no pisar el existente.
        // Si viene, pasamos el string limpio (el cast del Modelo se encargará del hasheo único).
        if (!$request->filled('password')) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Ficha del usuario actualizada con éxito.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Acceso denegado. No puedes eliminar la cuenta con la que te encuentras logueado.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'El registro de usuario ha sido removido del sistema de forma segura.');
    }
}