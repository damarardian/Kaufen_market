<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Data;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Deposit::select("*"); 
        if ($request->keyword) {
            $query = $request->keyword;     
            $data->where(function ($q) use($query){
                $q->where('name','LIKE', "%".$query."%");//untuk membuat fitur search ( "%{$query}%" / "%".$query."%" )                 ;
            });

        }
        return $data->paginate(10);
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
        // $getuser = Auth::user();
        // $getname = $getuser['name'];
        $deposit = new Deposit;       
        $deposit->user_id = $request->user_id;
        $deposit->data_id = $request->data_id;
        // $deposit->name = $request->name;
        $deposit->total = $request->total;
        // $deposit->data_name = $request->data_name;
        if ($deposit->save()) {
            return ["status" => "Berhasil Menyimpan Data"];
        } else {
            return ["status" => "Gagal Menyimpan Data"];
        }
    }

    public function history(User $user)
    {
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $deposit = $user->find($userId)->deposit()->orderBy("id", "desc")->get("*");
        $count = count($deposit);
        for ($i=0; $i < $count; $i++) {
            $deposit[$i]['created'] = Carbon::parse($deposit[$i]['created_at'])->diffForHumans();
        }
        return response()->json(["message" => "success", "data" => $deposit], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Deposit::where('id',$id)->exists()) {
            Deposit::where('id',$id)->first();
        } else{
            return response()->json([
                "message" => "id Not Found"
            ], 404);
        }
        return Deposit::where('id',$id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Deposit::where('id', $id)->first();
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
        $deposit = Deposit::where('id', $id)->first();
        // $deposit->name = $request->name;
        $deposit->total = $request->total;
        if ($deposit->save()) {
            return ["status" => "Berhasil Mengupdate Data"];
        } else {
            return ["status" => "Gagal Mengupdate Data"];
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
        $deposit =  Deposit::where('id', $id)->first();
        if ($deposit->delete()) {
            return ["status" => "Berhasil Menghapus Data"];
        } else {
            return ["status" => "Gagal Menghapus Data"];
        }
    }
}
