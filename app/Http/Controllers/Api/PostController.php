<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */


     public function store(Request $request)
    {
                //Validação dos dados pra saber se esta tudo de acordo com o que se espera deles
        $request->validate([
            //'name' => 'required|string|max:20',
            //'email' => 'required|email|unique:mauricio@mail.com',
            // 'categoria' => 'required|string|administador, cliente'

        ]);

        //salvando dados no banco de dados
        $dadosUsuario = new User;

        $dadosUsuario->name = $request->name;
        $dadosUsuario->email = $request->email;
        $dadosUsuario->categoria = $request->categoria;
        $dadosUsuario->password = $request->password;

        $dadosUsuario->save();
      
        //Backlog -- rotorno de resposta para o usuário
        return response()->json([
            'mensagem' => 'dados salvos com sucesso!',
            'dados' => $dadosUsuario,
        ], 201); //cria o status 201
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
