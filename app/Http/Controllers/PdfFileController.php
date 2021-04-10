<?php

namespace App\Http\Controllers;

use App\Models\UploadDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PdfFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('uploadfile');
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
        $validator = Validator::make($request->all(),[
            'fileToUpload' => 'required|file|mimes:pdf|max:1024',
        ]);
        if ($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors()->first(),
                'data'=>$validator->errors()->messages()
            ],422);
        }

        $file = $request->file('fileToUpload');

        $destnation='logos/'.$file->getClientOriginalName();

        $check = UploadDocument::where(['user_id'=>Auth::user()->id,'file_name'=>$destnation])->get();
        if (count($check)===0){
            if(Storage::disk('public')->exists($destnation)){
                $content = str_contains(Storage::disk('public')->get($destnation),'Proposal');
                return response()->json([
                    'status'=>false,
                    'message'=>'FIle Already Exists',
                    'data'=>[]
                ],422);
            }
            $status = Storage::disk('public')->put($destnation,$file->getContent());
            if ($status){
                $uploadDocument = new UploadDocument();
                $uploadDocument->user_id = Auth::user()->id;
                $uploadDocument->file_name = $destnation;
                $uploadDocument->save();
                return response()->json([
                    'status'=>true,
                    'message'=>'You have successfully upload file.',
                    'data'=>[]
                ],200);
            }
            return response()->json([
                'status'=>false,
                'message'=>'Something Went Wrong!.',
                'data'=>[]
            ],422);
        }

        return response()->json([
            'status'=>false,
            'message'=>'This File Already Uploaded',
            'data'=>[]
        ],422);



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
    }
}
