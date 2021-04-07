<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Decstock;
use Carbon\Carbon;
use App\Models\User;


use Illuminate\Http\Request;

class DecstockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Decstock::all()->first();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dec = new Decstock;
        $dec->barang_id = $request->barang_id;
        $dec->dec_stock = $request->dec_stock;   
        if ($dec->save()) {
            return ["status" => "Berhasil Menyimpan decrement"];
        } else {
            return ["status" => "Gagal Menyimpan decrement"];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Decstock  $decstock
     * @return \Illuminate\Http\Response
     */
    public function show(Decstock $decstock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Decstock  $decstock
     * @return \Illuminate\Http\Response
     */
    public function edit(Decstock $decstock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Decstock  $decstock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Decstock $decstock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Decstock  $decstock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Decstock $decstock)
    {
        $dec = Decstock::where('id', $id)->first();
        if ($dec->delete()) {
            return ["status" => "Berhasil Menghapus Data"];
        } else {
            return ["status" => "Berhasil Menghapus Data"];
        }
    }

    public function history(User $user)
    {
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $decStock = $user->find($userId)->barang()->orderBy("id", "desc")->get("*");
        $count = count($decStock);
        for ($i=0; $i < $count; $i++) {           
            $decStock[$i]['created'] = Carbon::parse($decStock[$i]['created_at'])->diffForHumans();
        }
        return response()->json(["message" => "success", "data" => $decStock], 200);
    }
}
