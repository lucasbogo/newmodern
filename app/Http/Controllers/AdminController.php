<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Metodo para encerrar sessão mantenedor (logout admin)
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Metodo para buscar perfil mantenedor
    public function Profile(Request $request)
    {
        // Pegar a sessaõ do usuario logado pelo id
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    // Metodo editar perfil mantenedor
    public function EditProfile(Request $request)
    {
        // Pegar a sessaõ do usuario logado pelo id
        $id = Auth::user()->id;
        $editData = User::find($id);
        return view('admin.admin_profile_edit', compact('editData'));
    }

    // Metodo...
    public function StoreProfile(Request $request)
    {
        // Pegar a sessaõ do usuario logado pelo id
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;

        // Verificar se existe arquivo 'image'
        if ($request->file('profile_image')) {

            $file = $request->file('profile_image');

            // Pega o nome Cliente e data e armazena na variável $filename
            $filename = date('YmdHi') . $file->getClientOriginalName();

            // Especificar local onde dados da imagem armazenados em $filename será armazenado
            $file->move(public_path('upload/admin_images'), $filename);

            // Adicionar dados no BD
            $data['profile_image'] = $filename;
        }
        // Salvar os dados no BD
        $data->save();

        // Após todo o processo, redirecionar para profile
        return redirect()->route('admin.profile');
    }
}
