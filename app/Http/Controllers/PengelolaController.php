<?php

namespace App\Http\Controllers;

use App\Models\PengelolaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PengelolaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Pengelola',
            'list'  => ['Home', 'Pengelola']
        ];

        $page = (object) [
            'title' => 'Data Pengelola Kos'
        ];

        $activeMenu = 'pengelola';

        return view('pengelola.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $data = PengelolaModel::select(
            'id_pemilik',
            'nama',
            'alamat',
            'no_hp',
            'jenis_kelamin'
        );

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn  = '<button onclick="modalAction(\'' . url('/pengelola/' . $row->id_pemilik . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengelola/' . $row->id_pemilik . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengelola/' . $row->id_pemilik . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function create()
    {
        return view('pengelola.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:50',
            'alamat'        => 'required|string|max:225',
            'no_hp'         => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        $data = new PengelolaModel();
        $data->nama          = $request->nama;
        $data->alamat        = $request->alamat;
        $data->no_hp         = $request->no_hp;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->save();

        return response()->json([
            'status'  => true,
            'message' => 'Data pengelola berhasil disimpan.'
        ], 200);
    }

    public function show($id)
    {
        $pengelola = PengelolaModel::findOrFail($id);
        return view('pengelola.show', compact('pengelola'));
    }

    public function edit($id)
    {
        $pengelola = PengelolaModel::findOrFail($id);
        return view('pengelola.edit', compact('pengelola'));
    }

    public function update(Request $request, $id)
    {
        $pengelola = PengelolaModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:50',
            'alamat'        => 'required|string|max:225',
            'no_hp'         => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'nama',
            'alamat',
            'no_hp',
            'jenis_kelamin',
        ]);

        $pengelola->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Data pengelola berhasil diperbarui.'
        ]);
    }

    public function confirm($id)
    {
        $pengelola = PengelolaModel::findOrFail($id);
        return view('pengelola.confirm', compact('pengelola'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $pengelola = PengelolaModel::find($id);

            if (!$pengelola) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            $pengelola->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        }

        return redirect()->route('pengelola.index');
    }
}