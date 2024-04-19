<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstagramFeed;
use App\Models\InstagramFetchFeed;
use Carbon\Carbon;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use Illuminate\Support\Facades\Http;
use App\Models\InstagramAccessToken;

class InstagramFeedController extends Controller
{
    use CrudControllerTrait;

    public function model()
    {
        $action_method = explode('/', url()->full());
        if (in_array("instagram-feeds", $action_method)) {
            return InstagramFeed::class;
        }
    }

    public function list()
    {
        if (request()->ajax()) {
            $model = InstagramFeed::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->instagram_feed_id . '" />';
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
            $table->addColumn('view_instagram', function ($row) {
                return '<a href="' . $row->permalink . '" class="btn btn-primary" target="_blank">View</a />';
            });
            $table->editColumn('media_url', function ($row) {
                if($row->media_type == 'VIDEO'){
                    return '<video width="50%" controls><source src="' . $row->media_url . '" type="video/mp4"></video>';
                }else{
                    return '<img src="' . $row->media_url . '" class="card-img-top" alt="Instagram Feed" style="width:50px!important" height="50">';
                }
            });
            // $table->editColumn('action', function ($row) {
            //     $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->instagram_feed_id]);
            //     return $action;
            // });
            $table->rawColumns(['checkbox', 'view_instagram','media_url']);
            return $table->make(true);
        }

        $pageData['page_title'] = "Manage Instagram feeds";
        $pageData['meta_title'] = "Manage Instagram feeds";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage Instagram feeds',
                'url' => route('pnkpanel.instagram-feeds.list')
            ]
        ];
        return view('pnkpanel.instagram_feed.instagram_feed_list')->with($pageData);
    }
    
    public function fetch()
    {
		$getInstagramSettings = InstagramAccessToken::first();
        if(empty($getInstagramSettings)){
            session()->flash('site_common_msg_err', 'Access Token is not set. Please set the access token first.');
            return redirect()->back();
        }
		$accessToken = $getInstagramSettings->instagram_access_token;
		$response = Http::get("https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url,username&access_token={$accessToken}");

        // id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,username,comments_count,like_count,children

		$data = $response->json();
        if(isset($data['data']) && count($data['data']) > 0) {
            $delete_feed = InstagramFetchFeed::truncate();
            foreach($data['data'] as $key => $value) {

                // Check if the feed already exists in the InstagramFeed table
                $exists = InstagramFeed::where('instagram_post_id', $value['id'])->exists();
                if (!$exists) {
                    $instagram_fetch_feed = new InstagramFetchFeed;
                    $instagram_fetch_feed->instagram_post_id = $value['id'];
                    $instagram_fetch_feed->media_type = $value['media_type'];
                    $instagram_fetch_feed->media_url = $value['media_url'];
                    $instagram_fetch_feed->permalink = $value['permalink'];
                    $instagram_fetch_feed->username = $value['username'];
                    $instagram_fetch_feed->save();
                }
            }
        }
        $prefix = 'Show';
        $pageData['page_title'] = $prefix . ' Instagram feeds';
        $pageData['meta_title'] = $prefix . ' Instagram feeds';
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage Instagram feeds',
                'url' => route('pnkpanel.instagram-feeds.list')
            ]
        ];
        $instagram_fetch_feed = InstagramFetchFeed::all();
        $pageData['instagram_fetch_feeds'] = $instagram_fetch_feed;
        return view('pnkpanel.instagram_feed.instagram_feed_show')->with($pageData);
    }

    public function instagram_feed_accept(Request $request)
    {
        if(isset($request->feed) && count($request->feed) > 0){
            foreach($request->feed as $key => $value) {
                $instagram_fetch_feed = InstagramFetchFeed::find($key);
                $instagram_feed = new InstagramFeed;
                $instagram_feed->instagram_post_id = $instagram_fetch_feed->instagram_post_id;
                $instagram_feed->media_type = $instagram_fetch_feed->media_type;
                $instagram_feed->media_url = $instagram_fetch_feed->media_url;
                $instagram_feed->permalink = $instagram_fetch_feed->permalink;
                $instagram_feed->username = $instagram_fetch_feed->username;
                $instagram_feed->save();
            }
            $delete_feed = InstagramFetchFeed::truncate();
            return redirect()->route('pnkpanel.instagram-feeds.list');
        }else{
            session()->flash('site_common_msg_err', 'Please select at least one feed to accept.');
            return redirect()->back();
        }
    }

}
