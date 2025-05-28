<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    public function form()
    {
        return view('auth.register');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:10',
                'regex:/[a-zA-Z]/',      // al menos una letra
                'regex:/[\d\W]/'         // al menos un número o carácter especial
            ],
            'nombre' => 'required|string|max:255',
            'dia' => 'required|integer|min:1|max:31',
            'mes' => 'required|string',
            'anio' => 'required|integer|min:1900|max:' . date('Y'),
            'genero' => 'required|string',
        ]);

        // Convertir fecha de nacimiento a formato YYYY-MM-DD
        $fechaNacimiento = $request->anio . '-' . $this->mesANumero($request->mes) . '-' . str_pad($request->dia, 2, '0', STR_PAD_LEFT);

        // Crear usuario
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->nombre,
            'birth_date' => $fechaNacimiento,
            'genero' => $request->genero,
        ]);

        Auth::login($user);

        return redirect('/inicio')->with('success', '¡Bienvenido/a, tu cuenta ha sido creada!');
    }

    private function mesANumero($mes)
    {
        $meses = [
            'Enero' => '01',
            'Febrero' => '02',
            'Marzo' => '03',
            'Abril' => '04',
            'Mayo' => '05',
            'Junio' => '06',
            'Julio' => '07',
            'Agosto' => '08',
            'Septiembre' => '09',
            'Octubre' => '10',
            'Noviembre' => '11',
            'Diciembre' => '12',
        ];

        return $meses[$mes] ?? '01';
    }
}
