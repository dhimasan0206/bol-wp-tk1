<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $heads = [
            'ID',
            'Title',
            'Created At',
            'Updated At',
            ['label' => 'Actions', 'width' => 15],
        ];
        $config = [
            'data' => [],
            'order' => [[1, 'desc']],
            'columns' => [null, null, null, null, ['orderable' => false]],
        ];

        foreach (Video::all() as $key => $value) {
            $config['data'][] = [
                $value->id,
                $value->title,
                Carbon::parse($value->created_at)->format('d M Y H:i:s'),
                Carbon::parse($value->updated_at)->format('d M Y H:i:s'),
                '
                    <a
                        class="btn btn-xs btn-default text-teal mx-1 shadow"
                        title="Details"
                        role="button"
                        href="/video-view/'.$value->id.'"
                    >
                        <i class="fa fa-lg fa-fw fa-eye"></i>
                    </a>
                    <a 
                        class="btn btn-xs btn-default text-primary mx-1 shadow"
                        title="Edit"
                        role="button"
                        href="/video-edit/'.$value->id.'"
                    >
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </a>
                    <a 
                        class="btn btn-xs btn-default text-danger mx-1 shadow"
                        title="Delete"
                        role="button"
                        href="/video-delete/'.$value->id.'"
                    >
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </a>
                '
            ];
        }
        
        return view('video-list', compact('heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('video-upload');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimetypes:video/mp4',
        ]);
 
        $fileName = $request->video->getClientOriginalName();
        $filePath = 'videos/' . uniqid() . '-' . $fileName;
 
        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));
 
        // File URL to access the video in frontend
        // $url = Storage::disk('public')->url($filePath);
 
        if ($isFileUploaded) {
            $video = new Video();
            $video->title = $request->title;
            $video->path = $filePath;
            $video->save();
 
            return back()
            ->with('success','Video has been successfully uploaded.');
        }
 
        return back()
            ->with('error','Unexpected error occurred');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return view('video-view', [
            'video' => $video,
            'url' => asset($video->path)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('video-edit', [
            'video' => $video,
            'url' => asset($video->path)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'video' => 'file|mimetypes:video/mp4',
        ]);

        $video = Video::findOrFail($id);
        $video->title = $request->title;

        if (!is_null($request->video)) {
            $fileName = $request->video->getClientOriginalName();
            $filePath = 'videos/' . uniqid() . '-' . $fileName;
            $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));
            if (!$isFileUploaded) {
                return back()
                    ->with('error','Unexpected error occurred');
            }
            $video->path = $filePath;
        }
        
        $video->save();
 
        return back()
            ->with('success','Video has been successfully uploaded.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $filepath = $video->path;
        $video->delete();
        Storage::disk('public')->delete($filepath);
        return to_route('list.video')->with('success', 'Video has been successfully deleted.');
    }
}
