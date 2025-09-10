<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        try {
            $kode = $request->get('kode');
            $nama = $request->get('nama');
            $hargamin = $request->get('hargamin');
            $hargamax = $request->get('hargamax');

            $data_search = MasterItem::with('categories');

            // Filter berdasarkan kode
            if (!empty($kode)) {
                $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
            }

            // Filter berdasarkan nama
            if (!empty($nama)) {
                $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
            }

            // Filter berdasarkan harga
            if (!empty($hargamin) && !empty($hargamax)) {
                $data_search = $data_search->whereBetween('harga_beli', [$hargamin, $hargamax]);
            } elseif (!empty($hargamin)) {
                $data_search = $data_search->where('harga_beli', '>=', $hargamin);
            } elseif (!empty($hargamax)) {
                $data_search = $data_search->where('harga_beli', '<=', $hargamax);
            }

            $data_search = $data_search->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')
                        ->orderBy('id', 'desc')
                        ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diambil',
                'data' => $data_search,
                'total' => $data_search->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in search: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan dalam mengambil data',
                'data' => []
            ], 500);
        }
    }

    public function formView($method, $id = 0)
    {
        try {
            if ($method == 'new') {
                $item = new MasterItem();
            } else {
                $item = MasterItem::with('categories')->findOrFail($id);
            }

            $categories = categories::all();

            $data = [
                'item' => $item,
                'method' => $method,
                'categories' => $categories
            ];

            return view('master_items.form.form', $data);

        } catch (\Exception $e) {
            return redirect('master-items')->with('error', 'Item tidak ditemukan');
        }
    }

    public function singleView($kode)
    {
        try {
            $data['data'] = MasterItem::with('categories')->where('kode', $kode)->firstOrFail();
            return view('master_items.single.index', $data);
        } catch (\Exception $e) {
            return redirect('master-items')->with('error', 'Item tidak ditemukan');
        }
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'harga_beli' => 'required|numeric|min:0',
                'laba' => 'required|numeric|min:0|max:100',
                'supplier' => 'required|string|max:255',
                'jenis' => 'required|string|max:100',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            if ($method == 'new') {
                $data_item = new MasterItem;
                $kode = MasterItem::withTrashed()->count() + 1;
                $kode = 'ITM' . str_pad($kode, 5, '0', STR_PAD_LEFT);
            } else {
                $data_item = MasterItem::findOrFail($id);
                $kode = $data_item->kode;
            }

            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($data_item->foto && Storage::exists('public/foto_items/' . $data_item->foto)) {
                    Storage::delete('public/foto_items/' . $data_item->foto);
                }

                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/foto_items', $fotoName);
                $data_item->foto = $fotoName;
            }

            // Update data
            $data_item->nama = $request->nama;
            $data_item->harga_beli = $request->harga_beli;
            $data_item->laba = $request->laba;
            $data_item->kode = $kode;
            $data_item->supplier = $request->supplier;
            $data_item->jenis = $request->jenis;
            $data_item->save();

            // Sync categories
            if ($request->has('categories')) {
                $data_item->categories()->sync($request->categories);
            } else {
                $data_item->categories()->detach();
            }

            $message = $method == 'new' ? 'Item berhasil ditambahkan' : 'Item berhasil diperbarui';
            return redirect('master-items')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error in formSubmit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam menyimpan data')->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $item = MasterItem::findOrFail($id);

            // Hapus foto jika ada
            if ($item->foto && Storage::exists('public/foto_items/' . $item->foto)) {
                Storage::delete('public/foto_items/' . $item->foto);
            }

            // Hapus relasi kategori
            $item->categories()->detach();

            // Soft delete
            $item->delete();

            return redirect('master-items')->with('success', 'Item berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error in delete: ' . $e->getMessage());
            return redirect('master-items')->with('error', 'Terjadi kesalahan dalam menghapus data');
        }
    }

    public function updateRandomData()
    {
        try {
            $data = MasterItem::get();
            foreach($data as $item) {
                $kode = 'ITM' . str_pad($item->id, 5, '0', STR_PAD_LEFT);

                $item->harga_beli = rand(100, 1000000);
                $item->laba = rand(10, 99);
                $item->kode = $kode;
                $item->supplier = $this->getRandomSupplier();
                $item->jenis = $this->getRandomJenis();
                $item->save();
            }

            return redirect('master-items')->with('success', 'Data berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error in updateRandomData: ' . $e->getMessage());
            return redirect('master-items')->with('error', 'Terjadi kesalahan dalam memperbarui data');
        }
    }

    private function getRandomSupplier()
    {
        $suppliers = ['Tokopedia', 'Bukalapak', 'TokoBagas', 'E-Commerce', 'BlibliMart'];
        return $suppliers[array_rand($suppliers)];
    }

    private function getRandomJenis()
    {
        $jenis = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        return $jenis[array_rand($jenis)];
    }

    /**
     * Get item photo
     */
    public function getPhoto($filename)
    {
        $path = storage_path('app/public/foto_items/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
