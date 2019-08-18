<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pegawaiModel;

class pegawaiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $req)
    {
        //
        $orderBy = 'id';
        $sortBy = 'asc';
        $q = null;
        $perPage = 20;
        $status = null;

        if($req->has('orderBy')) $orderBy = $req->query('orderBy');
        if($req->has('sortBy')) $sortBy = $req->query('sortBy');
        if($req->has('query')) $q = $req->query('query');
        if($req->has('status')) $status = $req->query('status');
        if($req->has('perPage')) $perPage = $req->query('perPage');

        $pegawai = pegawaiModel::cari($q)->status($status)->orderBy($orderBy,$sortBy)->paginate($perPage);
        // $pegawai = pegawaiModel::cari($query)->orderBy($orderBy,$sortBy)->paginate($perPage);
        return view('pegawai.index',['pegawais'=>$pegawai]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:pegawai|email',
            'job' => 'required'
        ]);
        $pegawai = new pegawaiModel([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'job' => $request->get('job'),
            'status' => 0,
        ]);
        $pegawai->created_at = date('Y-m-d H:i:s',time());
        $pegawai->updated_at = date('Y-m-d H:i:s',time());
        // print_r($pegawai);
        // exit();
        $pegawai->save();
        return redirect('/pegawai')->with('success', 'Pegawai telah ditambahkan');
    }

    /**
     * Change Status in storage
     * 
     * @param \Illuminate\Http\Response $id
     * @param \Illuminate\Http\Response $status
     * @return Redirect Page
     */
    public function change($id,$status)
    {
        $pegawai = pegawaiModel::findOrFail($id);
        if($status == "active"){
            $pegawai->status = 1;
        }elseif($status == "non-active"){
            $pegawai->status = 0;
        }
        $pegawai->save();
        return redirect('/pegawai')->with('success','Status Berhasil Diubah');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $pegawai = pegawaiModel::findOrFail($id);
        return view('pegawai.edit',['pegawai'=>$pegawai]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:pegawai,email,'.$id,
            'job' => 'required'
        ]);

        $pegawai = pegawaiModel::findOrFail($id);

        $pegawai->name = $request->get('name');
        $pegawai->email = $request->get('email');
        $pegawai->job = $request->get('job');
        $pegawai->updated_at = date('Y-m-d H:i:s',time());

        $pegawai->save();

        return redirect('/pegawai')->with('success', 'Data Pegawai Berhasil Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $pegawai = pegawaiModel::findOrFail($id);
        $pegawai->delete();
        return redirect('/pegawai')->with('success', 'Berhasil Menghapus Pegawai');
    }
}