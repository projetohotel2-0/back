<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lanche;

class LancheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return Lanche::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
     
    // Validação dos dados recebidos
    $request->validate([
        'name' => 'required|string', // Campo obrigatório
        'description' => 'string|nullable',
        'type' => 'array|nullable', // JSON convertido automaticamente
        'promotion' => 'string|nullable',
        'discount' => 'numeric|nullable',
        'images' => 'nullable|string', // Base64 da imagem
    ]);
    // Salvar os dados principais no banco de dados
    $dadosLanche = new Lanche();


    $dadosLanche->fill([
        'name' => $request->name,
        'description' => $request->description,
        'type' => json_encode($request->type), // Convertendo array para JSON
        'promotion' => $request->promotion,
        'discount' => $request->discount,
    ]);
    

    // Processar a imagem codificada em Base64
    $base64Image = $request->images;

// Salvar os dados no banco de dados


    if (!empty($request->images)) {
        $base64Image = $request->images;
    
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageType = $matches[1];
            $base64ImageWithoutHeader = substr($base64Image, strpos($base64Image, ',') + 1);
            $decodedImage = base64_decode($base64ImageWithoutHeader);
    
            if ($decodedImage === false) {
                return response()->json(['message' => 'Falha ao decodificar a imagem.'], 400);
            }
    
            $fileName = uniqid() . '.' . $imageType;
            $filePath = "images/$fileName";
    
            // Salvar a imagem no disco
            file_put_contents(public_path($filePath), $decodedImage);
    
            // Criar os dados da imagem, incluindo o Base64 completo
            $imageData = [
                'base64' => $base64Image, // Inclui o Base64 completo no JSON
            ];
    
            $dadosLanche->images = json_encode($imageData);
        } else {
            return response()->json(['message' => 'Formato inválido de imagem Base64.'], 400);
        }

    } else {
        // Se a imagem não for enviada, defina como null ou vazio
        $dadosLanche->images = null;

    }

   
    $dadosLanche->save();


    // Retornar resposta de sucesso
    return response()->json([
        'mensagem' => 'Dados salvos com sucesso aqui!',
        'dados' => [
            'id' => $dadosLanche->id,
            'name' => $dadosLanche->name,
            'description' => $dadosLanche->description,
            'type' => $dadosLanche->type, // Decodifica JSON armazenado
            'promotion' => $dadosLanche->promotion,
            'discount' => $dadosLanche->discount,
       'images' => $dadosLanche->images ? json_decode($dadosLanche->images)->base64 : null,
        // 'images' => json_decode($dadosLanche->images, true)['base64'],
        ],
    ], 201);

                    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lanche = Lanche::find($id);

        if ($lanche) {
            $lanche->delete();
            return response()->json(['message' => 'Registro deletado com sucesso!'], 200);
        }
        return response()->json(['message' => 'Registro não encontrado!'], 404);

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
        $model = Lanche::find($id);

    // Verifica se o registro existe
    if (!$model) {
        return response()->json([
            'message' => 'Registro não encontrado.',
        ], 404); // Retorna erro 404
    }

    // Exclui o registro
    $model->delete();

    // Retorna uma resposta de sucesso
    return response()->json([
        'message' => 'Registro deletado com sucesso!',
    ], 200);
    }
    //Consultar lanche excluido
    public function consultDestroy(){
        $lanchesExcluidos = Lanche::onlyTrashed()->get();
        return response()->json($lanchesExcluidos);
    }
    public function consultRestore($id){
        $registro = Lanche::withTrashed()->find($id);

        if ($registro) {
            $registro->restore();
            return response()->json(['message' => 'Registro restaurado com sucesso!']);
        } 
    
        return response()->json(['message' => 'Registro não encontrado!'], 404);
    }
}
