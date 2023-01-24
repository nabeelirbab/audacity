<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VideoPost;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Column\Help;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Box;

class MyVideoPostController extends Controller
{

    public function index(Content $content) {
        $content->header(___('labels.title'));
        $content->description(___('labels.description'));

        $posts = VideoPost::whereManagerId(Admin::user()->manager_id)->public()->get();

        $n = count($posts);

        for( $i = 0; $i < $n; $i++) {

            $row = new Row;

            for($j = 0; $j < 3; $j++) {
                $this->prepareColumn($row, $posts[$i], 4);
                $i++;
                if($i >= $n) {
                    break;
                }
            }
            $i--;

            $content->row($row);
        }

        return $content;
    }

    private function prepareColumn(Row $row, VideoPost $post, int $size) {

        $title = $post->title;
        $title .= (new Help($post->description))->render();

        $preview = '';
        switch($post->type) {
            case 1:
                $preview = '<iframe src="https://www.youtube.com/embed/'.$post->video_id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                break;
            case 2:
                $preview = '<iframe src="https://player.vimeo.com/video/'.$post->video_id.'" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                break;
        }

        $row->column($size, (new Box($title, $preview)));
    }
}