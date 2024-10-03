<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Http\Requests\StoreDownloadRequest;
use App\Http\Requests\UpdateDownloadRequest;
use App\Http\Resources\DownloadResource;

class DownloadController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display a listing of the resource.
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $downloads = Download::with(['user', 'book'])->get();
        
        return DownloadResource::collection($downloads);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created resource in storage.
    |--------------------------------------------------------------------------
    */
    public function store(StoreDownloadRequest $request)
    {
        $download = Download::create($request->validated());

        return new DownloadResource($download);
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified resource.
    |--------------------------------------------------------------------------
    */
    public function show(Download $download)
    {
        $download->load(['user', 'book']);

        return new DownloadResource($download);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified resource in storage.
    |--------------------------------------------------------------------------
    */
    public function update(UpdateDownloadRequest $request, Download $download)
    {
        $download->update($request->validated());

        return new DownloadResource($download);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified resource from storage.
    |--------------------------------------------------------------------------
    */
    public function destroy(Download $download)
    {
        $download->delete();

        return response()->noContent();
    }
}
