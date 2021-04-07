<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Loans;
use App\Models\Pay;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Loans::where('waiting_confirmation', 'accepted')->orderBy("updated_at", "desc"); 
        if ($request->keyword) {
            $query = $request->keyword;     
            $data->where(function ($q) use($query){
                $q->where('total','LIKE', "%".$query."%")//untuk membuat fitur search ( "%{$query}%" / "%".$query."%" )
                 ->orwhere('return','LIKE', "%".$query."%");
            });
        };
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
    public function store(Request $request, User $user)
    {
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $userName = $getuser['name'];

        $input = $request->all();
        $input['user_id'] = $userId;
        $input['name'] = $userName;
        $input['data_id'] = $request->data_id;
        $input['total'] = $request->total;
        $input['waiting_confirmation'] = $request->waiting_confirmation;
        $input['return'] = $request->return;
        $loan = Loans::create($input);

        if ($loan) {
            return response()->json(['message' => "Succesfully sending Loan request", "data" => $input], 200);                        
        } else {
            return response()->json(['error' => "Failed sending Loan request", "data" => $input]);            
        }

        // $loan = new Loans;
        // $loan->user_id = $request->user_id;
        // $loan->data_id = $request->data_id;
        // // $loan->name = $request->name;
        // $loan->total = $request->total;
        // $loan->return = $request->return;
        // if ($loan->save()) {
        //     return ["status" => "Berhasil Menyimpan Data"];
        // } else {
        //     return ["status" => "Gagal Menyimpan Data"];
        // }

    }

    public function loanAccepting($id, Request $request)
    {
        if (Loans::where('id',$id)->first() == null) {
            return response()->json(["error" => "there is no pay request with id " . $id], 404);        
        }

        // $payReq = Pay::where('id', $id)->get('waiting_confirmation');
        if ($request->waiting_confirmation == 'accepted') {
            $validator = Validator::make($request->all(), [
                "waiting_confirmation" => "required",               
            ]);

            if($validator->fails()) {
                return response()->json(["error" => $validator->errors()], 401);
            }

            $input = $request->all();
            Loans::where("id", $id)
            ->update([
                    'waiting_confirmation' => $input['waiting_confirmation'],
                ]);

            return response()->json(["message" => "The Loan Has been Accepted"], 200);

        }

        else {
            return response()->json(["message" => "The Loan Has Been Rejected "], 200);

        }
    }

    public function loanRequestList()
    {

        $data = Loans::where('waiting_confirmation', null)->orderBy("created_at", "desc")->get();
        
        return json_decode($data);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Loans::where('id',$id)->exists()) {
            Loans::where('id',$id)->first();
        } else{
            return response()->json([
                "message" => "id Not Found"
            ], 404);
        }
        return Loans::where('id',$id)->get();    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Loans::where('id', $id)->first();
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
        $loan = Loans::where('id', $id)->first();
        // $loan->name = $request->name;        
        $loan->total = $request->total;
        // $loan->return = $request->return;
        if ($loan->save()) {
            return ["status" => "Berhasil Merubah Data"];
        } else {
            return ["status" => "Gagal Merubah Data"];
        }
    }
        
    public function history(User $user)
    {
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $loan = $user->find($userId)->loan()->orderBy("id", "desc")->get("*");
        $count = count($loan);
        for ($i=0; $i < $count; $i++) {
            $loan[$i]['created'] = Carbon::parse($loan[$i]['created_at'])->diffForHumans();
        }
        return response()->json(["message" => "success", "data" => $loan], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loans::where('id', $id)->first();
        if ($loan->delete()) {
            return ["status" => "Berhasil Menghapus Data"];
        } else {
            return ["status" => "Berhasil Menghapus Data"];
        }
    }


    /**
     *                                          Pay Loan State
     */


    // Pay Loan

    public function requestPay(Request $request, User $user)
    {
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $userName = $getuser['name'];
        $input = $request->all();



        $validator = Validator::make($input, [            
            "nominal" => "required",            
        ]);
        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input['nominal'] = $request->nominal;
        // $input['waiting_confirmation'] = $request->waiting_confirmation;
        $input['user_id'] = $userId;
        $input['name'] = $userName;
        $pay = Pay::create($input);

        if ($pay) {
            return response()->json(['message' => "Succesfully sending Pay request", "data" => $input], 200);            
        } else {
            return response()->json(['error' => "Failed sending Pay request", "data" => $input]);            
        }

    }

    // Pay loan accepting
    public function payAccepting($id, Request $request)
    {
        if (Pay::where('id',$id)->first() == null) {
            return response()->json(["error" => "there is no pay request with id " . $id], 404);        
        }

        // $payReq = Pay::where('id', $id)->get('waiting_confirmation');
        if ($request->waiting_confirmation == 'accepted') {
            $validator = Validator::make($request->all(), [
                "waiting_confirmation" => "required",               
            ]);

            if($validator->fails()) {
                return response()->json(["error" => $validator->errors()], 401);
            }

            $input = $request->all();
            Pay::where("id", $id)
            ->update([
                    'waiting_confirmation' => $input['waiting_confirmation'],
                ]);

            return response()->json(["message" => "The Pay Has been Accepted"], 200);

        }

        else {
            return response()->json(["message" => "The Pay Has Been Rejected "], 200);

        }
    }

    // pay accepted history
    public function payAcceptedHistory(User $user, $per_page = 5)
    {
        $data = Pay::where('waiting_confirmation', 'accepted')->orderBy("created_at", "desc")->get();
        return $data;
    }

    // All Pay history
    public function payHistory(User $user, $per_page = 5)
    {
        $getuser = Auth::user();
        $userId = $getuser['id'];
        $pay = $user->find($userId)->pay()->orderBy("id", "desc")->paginate($per_page);
        $count = count($pay);
        for ($i=0; $i < $count; $i++) {
            $pay[$i]['created'] = Carbon::parse($pay[$i]['created_at'])->diffForHumans();
        }
        return response()->json($pay, 200);
    }

    // Show Pay Loan List
    public function payRequestList()
    {

        $data = Pay::where('waiting_confirmation', null)->orderBy("created_at", "desc")->get();
        return $data;
        
        // if ($data == null) {
        //     return response()->json(["message" => "waiting_list", "data" => $data], 200);            
        // }
        // else {
        //     return response()->json(["message" => "No_waitng", "data" => $data], 200);            
        // }
    }
}
