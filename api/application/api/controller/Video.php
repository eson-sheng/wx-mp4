<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-04-15
 * Time: 13:52
 */

namespace app\api\controller;

use think\Controller;

class Video extends Controller
{
    public function index ()
    {
        $page = $this->request->param('page', FALSE);

        $validate_Video = new \app\api\validate\Video();
        return $validate_Video->check_getList($page);
    }

    public function get ()
    {
        $id = $this->request->param('id', FALSE);

        $validate_Video = new \app\api\validate\Video();
        return $validate_Video->check_getVideoInfo($id);
    }

    public function upload ()
    {
        $name = $this->request->param('name', FALSE);
        $author = $this->request->param('author', FALSE);
        $file = $this->request->file('file');

        $upload_video = __DIR__ . "/../../../public/upload_video";
        if (!file_exists($upload_video)) {
            mkdir($upload_video, 0777);
        }

        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload_video');

        $validate_Video = new \app\api\validate\Video();
        return $validate_Video->add($name, $author, $info->getSaveName());
    }
}