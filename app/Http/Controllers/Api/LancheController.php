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
        // 'name' => 'required|string',
        // 'description' => 'nullable|string',
        // //'type' => 'nullable|array', // Se for JSON, será convertido para array automaticamente
        // 'promotion' => 'nullable|boolean',
        // 'discount' => 'nullable|numeric',
        'images' => 'required|string', // Recebe a string Base64 da imagem
    ]);

    // Salvar os dados principais no banco de dados
    $dadosLanche = new Lanche();
    $dadosLanche->name = $request->name;
    $dadosLanche->description = $request->description;
    $dadosLanche->type = json_encode($request->type); // Convertendo para JSON
    $dadosLanche->promotion = $request->promotion;
    $dadosLanche->discount = $request->discount;
    

    // Processar a imagem codificada em Base64
    $base64Image = $request->images;

    // // Validar o formato Base64
    // if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
    //     $imageType = $matches[1]; // Obtém o tipo da imagem (png, jpeg, etc.)
    //     $base64Image = substr($base64Image, strpos($base64Image, ',') + 1); // Remove cabeçalho Base64
    //     $decodedImage = base64_decode($base64Image); // Decodifica a imagem

    //     // Gerar nome único para o arquivo
    //     $fileName = uniqid() . '.' . $imageType;
    //     $filePath = "images/$fileName";

    //     // Salvar a imagem no sistema de arquivos
    //     file_put_contents(public_path($filePath), $decodedImage);

    //     // Criar os dados da imagem para salvar no JSON
    //     $imageData = [
    //         'filename' => $fileName,
    //         'path' => $filePath,
    //         'url' => asset($filePath), // URL acessível da imagem
    //     ];

    //     // Adicionar as informações da imagem ao modelo
    //     $dadosLanche->images = json_encode($imageData);
    // } else {
    //     return response()->json(['message' => 'Formato de imagem inválido'], 400);
    // }



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
           
           'base64'=> $base64Image, // Inclui o Base64 completo no JSON
            
        ];
    
        $dadosLanche->images = json_encode($imageData);
        
    } else {
        return response()->json(['message' => 'Formato inválido de imagem Base64.'], 400);
    }





    // Salvar os dados no banco de dados
    $dadosLanche->save();

    // Retornar resposta de sucesso
    return response()->json([
        'mensagem' => 'Dados salvos com sucesso!',
        'dados' => [
            'id' => $dadosLanche->id,
            'name' => $dadosLanche->name,
            'description' => $dadosLanche->description,
            'type' => $dadosLanche->type, // Decodifica JSON armazenado
            'promotion' => $dadosLanche->promotion,
            'discount' => $dadosLanche->discount,
        //    'images' => [
        //     'base64' => json_decode($dadosLanche->images)->base64, // Retorna apenas o Base64
        // ],
        //'images' => json_decode($dadosLanche->images),
        
        'images' => json_decode($dadosLanche->images)->base64,
       
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
