<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Muestra el panel principal con todos los registros.
     */
    public function index()
    {
        // Obtenemos todo el personal clínico ordenado por el ingreso más reciente
        $users = User::orderBy('id', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Registra un nuevo miembro del personal médico o administrativo.
     */
    public function store(Request $request)
    {
        // Validaciones estrictas para proteger la integridad de la Base de Datos
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username',
            'dni'      => 'nullable|string|digits:8|unique:users,dni',
            'cmp'      => 'nullable|string|max:10|unique:users,cmp',
            'rne'      => 'nullable|string|max:10|unique:users,rne',
            'role'     => 'required|in:superadmin,admin,medico,enfermera',
            'state'    => 'required|in:ACTIVO,INACTIVO',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'dni.digits' => 'El número de DNI debe contener exactamente 8 dígitos.',
            'unique'     => 'El parámetro ingresado en :attribute ya se encuentra registrado en el sistema clínico.'
        ]);

        // Guardamos el registro de forma segura (password se hashea mediante el cast del Modelo)
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Personal registrado correctamente en la plataforma.');
    }

    /**
     * Actualiza la información de un usuario existente.
     */
    public function update(Request $request, User $user)
    {
        // Validación dinámica ignorando el ID actual para evitar conflictos de campos 'unique'
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'dni'      => ['nullable', 'string', 'digits:8', Rule::unique('users')->ignore($user->id)],
            'cmp'      => ['nullable', 'string', 'max:10', Rule::unique('users')->ignore($user->id)],
            'rne'      => ['nullable', 'string', 'max:10', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:superadmin,admin,medico,enfermera',
            'state'    => 'required|in:ACTIVO,INACTIVO',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6', // Opcional al momento de editar
        ], [
            'dni.digits' => 'El número de DNI debe contener exactamente 8 dígitos.',
            'unique'     => 'Los datos de este :attribute ya pertenecen a otro miembro del personal.'
        ]);

        // Filtro de protección: Evitar que el usuario logueado se desactive a sí mismo
        if (auth()->id() === $user->id && $request->state === 'INACTIVO') {
            return redirect()->route('users.index')->with('error', 'Operación cancelada. No puedes cambiar tu propio estado a INACTIVO.');
        }

        // Gestión selectiva de la contraseña
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            // Remueve la contraseña del array de actualización si se dejó vacía en el modal
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Ficha del usuario actualizada con éxito.');
    }

    /**
     * Elimina de manera definitiva un registro del sistema.
     */
    public function destroy(User $user)
    {
        // Filtro de restricción crítica de accesos
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Acceso denegado. No puedes eliminar la cuenta con la que te encuentras logueado.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'El registro de usuario ha sido removido del sistema de forma segura.');
    }
}