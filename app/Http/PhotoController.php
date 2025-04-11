<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Str;
use Intervention\Image\Facades\Image;


class PhotoController extends Controller
{
    public function showCaptureForm($ida)
    {


        $associado = DB::table('associado AS ass')
            ->leftJoin('pessoas AS p', 'ass.id_pessoa', '=', 'p.id')
            ->where('ass.id', $ida)
            ->select(
                'ass.nr_associado',
                'ass.id',
                'p.nome_completo',
                'ass.dt_inicio',
                'ass.dt_fim'
            )->first();;
        //    dd($foto_associado);

        return view('/associado/capture-form', compact('associado', 'ida'));
    }

    public function storeCapturedPhoto(Request $request, $ida)
    {

        $photoData = $request->input('photo');

        $fotoConteudo = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photoData));

// Gerar hashcode
        $hashcode = str_replace(['+', '/', '='], '', base64_encode(random_bytes(10)));

        $nomeArquivo = $hashcode . '_' . time() . '_photo.jpg';




        $caminhoArquivo = Storage::disk('public')->put('fotos-pessoas/' . $nomeArquivo,$fotoConteudo);


        $caminho = ('/storage/fotos-pessoas/' . $nomeArquivo);




        if ($caminhoArquivo) {
            // DB::table('user_photos')->insert([
            //     'caminho_foto' => $caminho
            // ]);
            DB::table('associado')->where('id', $ida)->update(['caminho_foto_associado' => $caminho]);

            return redirect()->route('gerenciar-associado')->with('success', 'Foto salva com sucesso.');
        } else {
            return redirect()->route('gerenciar-associado')->with('error', 'Erro ao salvar a foto.');
        }


        // Retorna uma resposta de erro se a foto não for fornecida ou houver falha na decodificação
        return redirect()->back()->with('error', 'Erro ao salvar a foto.');
    }

    public function visualizarfoto()
    {

        $ultimoId = DB::table('user_photos')
            ->orderBy('id', 'desc')
            ->first()->id;
        // dd($ultimoId);


        $caminhodocumento = DB::table('user_photos AS us')
            ->where('us.id', $ultimoId)
            ->select(['us.caminho_foto'])
            ->first();

        //  dd($caminho);

        if ($caminhodocumento) {
            $caminho = $caminhodocumento->caminho_foto;

            //dd($caminho);

            if (Storage::exists($caminho)) {
                return response()->file(storage_path('app/' . $caminho));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }
}
