<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Barang;
use App\Models\Deposit;
use App\Models\Loans;
use App\Models\User;
use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public $successStatus = 200;

    
    // buat route role
    // public function __construct()
    // {
    //     $this->middleware(['role:admin']); 
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Data::with('loans', 'deposit', 'barang')->get()->all(); 
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
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $save = new Data;
        $save->user_id = $userId;
        $save->name = $request->name;
        $save->alamat = $request->alamat;
        $save->no_telp = $request->no_telp;
        $save->tanggal_lahir = $request->tanggal_lahir;
        // $save->posisi = $request->posisi;
        
        if ($save->save()) {
            return ["status" => "Berhasi Menyimpan Data", 201];
        }  else {
            return ["status" => "Gagal Menyimpan Data"];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Data::where('name',$id)->exists()) {
            Data::where('name',$id)->first();
        } else{
            return response()->json([
                "message" => "id Not Found"
            ], 404);
        }
        return Data::where('name',$id)->with('loans','deposit','barang')->get();    
        
    }

    // /**
    //  * Show untuk data diri sendiri
    //  */
    // public function showSelf()
    // {
    //     $user = Auth::user();
    //     $userId = $user['name'];    
    //     $user['loans'] = Loans::where('name', $userId)->get();
    //     $user['deposit'] = Deposit::where('name', $userId)->get();
    //     $user['barang'] = Barang::where('name', $userId)->get();

    //     return response()->json(['success' => $user], $this->successStatus);   
        
    // }    


    /**     
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Data::where('name', $id)->first();
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $save = Data::where('name', $id)->first();
        $save->name = $request->name;
        $save->alamat = $request->alamat;
        $save->no_telp = $request->no_telp;
        $save->tanggal_lahir = $request->tanggal_lahir;
        // $save->user_id = $request->user_id;       

        if ($save->save()) {
            return ["status" => "Berhasi Merubah Data", 201];
        }  else {
            return ["status" => "Gagal Merubah Data"];
        }
    }

    /**
     * UpdateSelf buat update data diri sendiri
     */
    public function updateSelf(Request $request)
    {
        $user = Auth::user();
        $userId = $user['name'];
        $save = Data::where('name', $userId)->first();
        $save->name = $request->name;
        $save->alamat = $request->alamat;
        $save->no_telp = $request->no_telp;
        $save->tanggal_lahir = $request->tanggal_lahir;
        // $save->user_id = $request->user_id;       

        if ($save->save()) {
            return ["status" => "Berhasi Merubah Data", 201];
        }  else {
            return ["status" => "Gagal Merubah Data"];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $save = Data::where('name',$id)->first();    
        if ($save->delete()) {
            return ["status" => "Berhasi Menghapus Data"];
        }  else {
            return ["status" => "Gagal Menghapus Data"];
        }
    }
}

