<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Images;

class ImagesController extends Controller
{

    public function index() {
        if (Auth::user()->profile == 'admin') {
            $images = Images::orderBy('updated_at', 'desc')->get();
        } else {
            $images = Images::where('user_id', '=', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
        }

        return view('images.index', [ 'images' => $images ]);
    }

    public function create() {
        $image = new Images();
        return view('images.create', ['image' => $image]);
    }

    public function store(Request $request) {
        // Validacao
        $validated = $request->validate([
            'name' => 'required|string|max:140',
            'description' => 'nullable|string|max:255',
            'image' => 'required|image|max:10000', // 10MB
        ]);

        // Cria a Imagem
        $image = new Images($validated);
        
        // Atualiza campo para insercao no banco
        $image['image'] = null;

        // Se estiver logado
        if (Auth::check()) {
            // Usuario logado
            $user = Auth::user();

            // Salva a noticia atraves do usuario logado
            $user->images()->save($image);
        } else {
            // Redireciona com a mensagem
            return redirect(route('images'))->with('error', __('Usuário não autenticado.'));
        }

        // Se houver arquivo e nao for nulo
        if ($request->hasFile('image') && $request->file('image')->isValid() && $validated['image'] != null) {

            // Faz o upload do arquivo para a pasta de imagens e gera o nome
            $filename = $request->file('image')->store('images', 'public');

            // Atualiza campo para atualizacao no banco
            $image->image = $filename;

            // Salva no banco o nome do arquivo
            $image->save();
        }

        // Redireciona com a mensagem
        return redirect(route('images.create'))->with('status', __('Imagem cadastrada com sucesso.'));
    }

    public function edit($id) {
        // Imagem
        $image = Images::findOrFail($id);

        return view('images.edit', ['image' => $image]);
    }

    public function update(Request $request, $id) {
        // Validacao
        $validated = $request->validate([
            'name' => 'required|string|max:140',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:10000', // 10MB
        ]);

        $image = Images::findOrFail($id);
        $image->name = $request->name;
        $image->description = $request->description;

        // Se houver arquivo e nao for nulo
        if ($request->hasFile('image') && $request->file('image')->isValid() && $validated['image'] != null) {

            // Faz o upload do arquivo para a pasta de imagens e gera o nome
            $filename = $request->file('image')->store('images', 'public');

            // Atualiza campo para atualizacao no banco
            $image->image = $filename;
        }

        $image->save();

        // Redireciona com a mensagem
        return redirect(route('images'))->with('status', __('Imagem atualizada com sucesso.'));
    }

    public function delete($id) {
        // Imagem
        $images = Images::findOrFail($id);

        // Senao for do usuario, retorna
        if ($images->user != Auth::user()) return back();

        // Exclui a Imagem
        $images->delete();

        // Retorna com a mensagem
        return redirect(route('images'))->with('status', __('Imagem excluída.'));
    }
}
