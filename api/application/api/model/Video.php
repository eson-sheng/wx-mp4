<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-04-15
 * Time: 16:30
 */

namespace app\api\model;

use app\api\lib\ResponseCode;
use think\Model;

class Video extends Model
{
    public $error = NULL;

    private function do_deal_with_file ($file)
    {
        $ret = [];
        $info = $this->get_video_info(__DIR__ . '/../../../public/upload_video/' . $file);
        $this->getVideoPhoto(__DIR__ . '/../../../public/upload_video/' . $file);

        $file_name = pathinfo($file, PATHINFO_FILENAME);

        $ret['duration'] = $info['duration'];
        $ret['img_src'] = "upload_video_img/{$file_name}.jpg";
        $ret['video_src'] = "upload_video/{$file}";

        return $ret;
    }

    private function get_video_info ($file)
    {
        $command = sprintf('ffmpeg -i "%s" 2>&1', $file);

        ob_start();
        passthru($command);
        $info = ob_get_contents();
        ob_end_clean();

        $data = array();
        if (preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $info, $match)) {
            $data['file_name'] = pathinfo($file)['basename']; //文件名称
            $data['duration'] = $match[1]; //播放时间
            $arr_duration = explode(':', $match[1]);
            $data['seconds'] = $arr_duration[0] * 3600 + $arr_duration[1] * 60 + $arr_duration[2]; //转换播放时间为秒数
            $data['start'] = $match[2]; //开始时间
            $data['bitrate'] = $match[3]; //码率(kb)
        }
        if (preg_match("/Video: (.*?), (.*?), (.*?)[,\s]/", $info, $match)) {
            $data['vcodec'] = $match[1]; //视频编码格式
            $data['vformat'] = $match[2]; //视频格式
            $data['resolution'] = $match[3]; //视频分辨率
//            $arr_resolution = explode('x', $match[3]);
//            $data['width'] = $arr_resolution[0];
//            $data['height'] = $arr_resolution[1];
        }
        if (preg_match("/Audio: (\w*), (\d*) Hz/", $info, $match)) {
            $data['acodec'] = $match[1]; //音频编码
            $data['asamplerate'] = $match[2]; //音频采样频率
        }
        if (isset($data['seconds']) && isset($data['start'])) {
            $data['play_time'] = $data['seconds'] + $data['start']; //实际播放时间
        }
        $data['size'] = filesize($file); //文件大小

        return $data;
    }

    private function getVideoPhoto ($file)
    {
        $upload_video_img = __DIR__ . "/../../../public/upload_video_img";
        if (!file_exists($upload_video_img)) {
            mkdir($upload_video_img, 0777);
        }

        $filename = pathinfo($file, PATHINFO_FILENAME);

        $command = sprintf('ffmpeg -i "%s" -y -f mjpeg -ss 8 -t 0.001 -s 192x108 "%s" 2>&1', $file, $upload_video_img . '/' . $filename . ".jpg");

        ob_start();
        passthru($command);
        $info = ob_get_contents();
        ob_end_clean();

        if (preg_match("/Output #0, mjpeg, to '(.*?)':/", $info, $match)) {
            $file_name = $match[1];
        }

        if (empty($file_name)) {
            return $info;
        } else {
            return $file_name;
        }
    }

    public function add_video_info ($name, $author, $file)
    {
        $file_res = $this->do_deal_with_file($file);

        $this->error = ResponseCode::SUCCESS;

        $param = [
            'name' => $name,
            'author' => $author,
            'src' => $file_res['video_src'],
            'img' => $file_res['img_src'],
            'length' => $file_res['duration'],
        ];

        $info = print_r($param, 1);
        \SeasLog::debug("\n$info\n");

        $video = new Video();
        if ($video->save($param)) {
            /*返回错误码*/
            $this->error = ResponseCode::SUCCESS;
        } else {
            $this->error = ResponseCode::SAVE_ERROR;
            return [];
        }

        return [
            'id' => $video->id
        ];
    }

    public function get_list ($page)
    {
        $video = new Video();

        $video_res = $video
            ->page($page, 10)
            ->column('name,img,src,author,length', 'id');

        if (!$video_res) {
            $this->error = ResponseCode::NOT_HAVE_DATA;
            return [];
        }

        /*数据修改去除关联键值为索引键值*/
        $arr = [];
        foreach ($video_res AS $k => $v) {
            $arr[] = $v;
        }

        $this->error = ResponseCode::SUCCESS;
        return $arr;
    }

    public function getVideoInfo ($id)
    {
        $video_obj = Video::get(['id' => $id, 'status' => 1]);
        if ($video_obj) {
            $this->error = ResponseCode::SUCCESS;

            $tmp_video = $video_obj->hidden([
                'status',
                'update_time',
            ])->toArray();

            return $tmp_video;
        }
        $this->error = ResponseCode::NOT_HAVE_DATA;
        return [];
    }
}