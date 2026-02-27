<?php

namespace App\Http\Controllers;

use App\Models\PenyewaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PenyewaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Penyewa',
            'list'  => ['Home', 'Penyewa']
        ];

        $page = (object) [
            'title' => 'Data Penyewa Kos'
        ];

        $activeMenu = 'penyewa';

        return view('penyewa.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $data = PenyewaModel::select(
            'id_penyewa',
            'nama',
            'alamat',
            'no_hp',
            'jenis_kelamin',
            'pekerjaan',
            'foto_ktp'
        );

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn  = '<button onclick="modalAction(\'' . url('/penyewa/' . $row->id_penyewa . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penyewa/' . $row->id_penyewa . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penyewa/' . $row->id_penyewa . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function create()
    {
        return view('penyewa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:50',
            'alamat'        => 'required|string|max:225',
            'no_hp'         => 'required|string|max:13',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'pekerjaan'     => 'required|string|max:100',
            'foto_ktp'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $filePath = null;
        
        // Handle upload file - PERBAIKI: ganti 'file' jadi 'foto_ktp'
        if ($request->hasFile('foto_ktp')) {
            $originalName = pathinfo($request->file('foto_ktp')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('foto_ktp')->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($originalName) . '.' . $extension;
            $request->file('foto_ktp')->storeAs('public/penyewa/ktp', $filename);
            $filePath = 'penyewa/ktp/' . $filename;
        }

        // Membuat data penyewa
        $data = new PenyewaModel();
        $data->nama = $request->nama;
        $data->alamat = $request->alamat;
        $data->no_hp = $request->no_hp;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->pekerjaan = $request->pekerjaan;
        $data->foto_ktp = $filePath;  // ✅ PERBAIKI: ganti forceDestroy jadi foto_ktp
        $data->save();

        return response()->json([
            'status'  => true,
            'message' => 'Data penyewa berhasil disimpan.'
        ], 200);
    }

    public function show($id)
    {
        $penyewa = PenyewaModel::findOrFail($id);
        return view('penyewa.show', compact('penyewa'));
    }

    public function edit($id)
    {
        $penyewa = PenyewaModel::findOrFail($id);
        return view('penyewa.edit', compact('penyewa'));
    }

    public function update(Request $request, $id)
    {
        $penyewa = PenyewaModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:50',
            'alamat'        => 'required|string|max:225',
            'no_hp'         => 'required|string|max:13',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'pekerjaan'     => 'required|string|max:100',
            'foto_ktp'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'nama',
            'alamat',
            'no_hp',
            'jenis_kelamin',
            'pekerjaan'
        ]);

        // Handle upload file baru - PERBAIKI: ganti 'file' jadi 'foto_ktp'
        if ($request->hasFile('foto_ktp')) {
            // Hapus file lama jika ada
            if ($penyewa->foto_ktp && Storage::exists('public/' . $penyewa->foto_ktp)) {
                Storage::delete('public/' . $penyewa->foto_ktp);
            }

            // Upload file baru
            $originalName = pathinfo($request->file('foto_ktp')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('foto_ktp')->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($originalName) . '.' . $extension;
            $request->file('foto_ktp')->storeAs('public/penyewa/ktp', $filename);
            $data['foto_ktp'] = 'penyewa/ktp/' . $filename;
        }

        $penyewa->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Data penyewa berhasil diperbarui.'
        ]);
    }

    public function confirm($id)
    {
        $penyewa = PenyewaModel::findOrFail($id);
        return view('penyewa.confirm', compact('penyewa'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $penyewa = PenyewaModel::find($id);

            if (!$penyewa) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            // Hapus file jika ada - PERBAIKI: ganti 'file' jadi 'foto_ktp'
            if ($penyewa->foto_ktp && Storage::exists('public/' . $penyewa->foto_ktp)) {
                Storage::delete('public/' . $penyewa->foto_ktp);
            }

            $penyewa->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        }

        return redirect()->route('penyewa.index');
    }
}