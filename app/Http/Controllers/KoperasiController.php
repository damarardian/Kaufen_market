<?php

namespace App\Http\Controllers;

use App\Models\Koperasi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\StatusCode;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class KoperasiController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Koperasi::select("*")->orderBy("name", "asc");
        if ($request->keyword) {
            $query = $request->keyword;     
            $data->where(function ($q) use($query){
                $q->where('name','LIKE', "%".$query."%")//untuk membuat fitur search ( "%{$query}%" / "%".$query."%" )
                 ->orWhere('jenis','LIKE', "%".$query."%")
                 ->orWhere('stock','LIKE', "%".$query."%")
                 ->orWhere('harga','LIKE', "%".$query."%");
            });
                                                   
        }

        //dd untuk mengecek apakah data sudah masuk atau belum
        return $data->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [            
            "image" => "required|image:jpeg,png,jpg|max:2048"
            ]);
        if($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 500);
        }  
        
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $input = $request->all();
        $input['user_id'] = $userId;
        $input['name'] = $request->name;
        $input['jenis'] = $request->jenis;
        $input['stock'] = $request->stock;
        $input['harga'] = $request->harga;

        // $barang = Barang::save();

        $img = $request->file('image');
        // $txt = $request->file('image')->guessExtension();
        $name_file = time()."_".$img->getClientOriginalName();

            // $imgUpload = new Image;
            // $imgUpload->name = $request->name;
            $input['image'] = $name_file;
            $img->move(public_path().'/img', $name_file);    
            Koperasi::create($input); 

            if ($input) {
                return ["status" => "Berhasi Menyimpan Barang dengan Image", 201];
            }  else {
                return ["status" => "Gagal Menyimpan Barang"];
            }
            
    }

    /**
     * Display History
     */

    public function history(User $user)
    {
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $koperasi = $user->find($userId)->koperasi()->orderBy("id", "desc")->get("*");
        $count = count($koperasi);
        for ($i=0; $i < $count; $i++) {
            $koperasi[$i]['created'] = Carbon::parse($koperasi[$i]['created_at'])->diffForHumans();
            $koperasi[$i]['updated'] = Carbon::parse($koperasi[$i]['updated_at'])->diffForHumans();
        }
        return response()->json(["message" => "success", "data" => $koperasi], 200);
    }

    /** 
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Koperasi::where('id',$id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Koperasi::where('id',$id)->first();       
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
        $koperasi = Koperasi::where('id',$id)->first();     
        $koperasi->name = $request->name;
        $koperasi->jenis = $request->jenis;
        $koperasi->stock = $request->stock;
        $koperasi->harga = $request->harga;                  
        if ($koperasi->save()) {
            return ["status" => "Berhasi Mengupdate Data", 201];
        }  else {
            return ["status" => "Gagal Mengupdate Data"];
        }   
    }

    public function updateImage(Request $request, $id)
    {
        $barang = Koperasi::find($id);

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time()."_".$extension;
            $file->move(public_path().'/img', $filename);
            $barang->image = $filename;

        }
        
        $barang->update();
        return response()->json(['image Updated' => $barang]);

        
    }

    /**
     * For Decreasing Stock only for seller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function transaksi(Request $request, $id)
    {
        $transaksi = Koperasi::where('id', $id)->first();
        // $transaksi->name = $request->name;
        // $transaksi->name_barang = $request->name_barang;
        // $transaksi->jenis = $request->jenis;
        // $transaksi->stock = $request->('stock');
        $transaksi->stock =  $request->stock;
        // $transaksi->stock =  stock - dec_stock;
        // $transaksi->harga = $request->harga;  
        if ($transaksi->save()) {
            return response()->json(['Terbeli' => $transaksi], 201);
        }  else {
            return ["status" => "Gagal Mlakukan Transaksi"];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $koperasi = Koperasi::where('id',$id)->first();    
        if ($koperasi->delete()) {
            return ["status" => "Berhasi Menghapus Data"];
        }  else {
            return ["status" => "Gagal Menghapus Data"];
        }
    }
}