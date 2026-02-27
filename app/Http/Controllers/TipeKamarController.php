<?php

namespace App\Http\Controllers;

use App\Models\TipeKamarModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TipeKamarController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Tipe Kamar',
            'list'  => ['Home', 'Tipe Kamar']
        ];

        $page = (object) [
            'title' => 'Data Tipe Kamar Kos'
        ];

        $activeMenu = 'tipe_kamar';

        return view('tipe_kamar.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $data = TipeKamarModel::select(
            'id_tipe_kamar',
            'jenis_tipe_kamar',
            'harga_perbulan',
            'fasilitas'
        );

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn  = '<button onclick="modalAction(\'' . url('/tipe_kamar/' . $row->id_tipe_kamar . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tipe_kamar/' . $row->id_tipe_kamar . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tipe_kamar/' . $row->id_tipe_kamar . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function create()
    {
        return view('tipe_kamar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_tipe_kamar' => 'required|string|max:100',
            'harga_perbulan'   => 'required|integer|min:0',
            'fasilitas'        => 'required|string',
        ]);

        TipeKamarModel::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Data tipe kamar berhasil disimpan.'
        ]);
    }

    public function show($id)
    {
        $tipeKamar = TipeKamarModel::find($id);

        if (!$tipeKamar) {
            abort(404);
        }

        return view('tipe_kamar.show', compact('tipeKamar'));
    }

    public function edit($id)
    {
        $tipeKamar = TipeKamarModel::find($id);

        if (!$tipeKamar) {
            abort(404);
        }

        return view('tipe_kamar.edit', compact('tipeKamar'));
    }

    public function update(Request $request, $id)
    {
        $tipeKamar = TipeKamarModel::find($id);

        if (!$tipeKamar) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        $validated = $request->validate([
            'jenis_tipe_kamar' => 'required|string|max:100',
            'harga_perbulan'   => 'required|integer|min:0',
            'fasilitas'        => 'required|string',
        ]);

        $tipeKamar->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Data tipe kamar berhasil diperbarui.'
        ]);
    }

    public function confirm($id)
    {
        $tipeKamar = TipeKamarModel::find($id);

        if (!$tipeKamar) {
            abort(404);
        }

        return view('tipe_kamar.confirm', compact('tipeKamar'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $tipeKamar = TipeKamarModel::find($id);

            if (!$tipeKamar) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            $tipeKamar->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        }

        return redirect()->route('tipe_kamar.index');
    }
}