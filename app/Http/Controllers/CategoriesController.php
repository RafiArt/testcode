<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = categories::all();
        return view('categories.index', compact('categories'));
    }
    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = categories::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('id', 'kode', 'nama')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = categories::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('categories.form', $data);
    }

    public function singleView($id)
    {
        $data['category'] = categories::with('masterItems')->findOrFail($id);
        return view('categories.single', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'kode' => 'required|unique:categories,kode,' . ($id ?: 'NULL'),
            'nama' => 'required'
        ]);

        if ($method == 'new') {
            $category = new categories;
        } else {
            $category = categories::findOrFail($id);
        }

        $category->kode = $request->kode;
        $category->nama = $request->nama;
        $category->save();

        return redirect('categories');
    }

    public function delete($id)
    {
        categories::findOrFail($id)->delete();
        return redirect('categories');
    }
}
