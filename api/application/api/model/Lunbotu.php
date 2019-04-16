<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-04-12
 * Time: 20:54
 */

namespace app\api\model;

use app\api\lib\ResponseCode;
use think\Model;

class Lunbotu extends Model
{
    public $error = NULL;

    public function get_img_info ()
    {
        $lunbotu = new Lunbotu();
        $lunbotu_res = $lunbotu
            ->column('title,img,href', 'id');

        if (!$lunbotu_res) {
            $this->error = ResponseCode::NOT_HAVE_DATA;
            return [];
        }

        /*数据修改去除关联键值为索引键值*/
        $arr = [];
        foreach ($lunbotu_res AS $k => $v) {
            $arr[] = $v;
        }

        $this->error = ResponseCode::SUCCESS;
        return $arr;
    }

    public function add_img_info ($title, $img, $href)
    {
        $param = [
            'title' => $title,
            'img' => $img,
            'href' => $href
        ];

        $lunbotu = new Lunbotu();
        if ($lunbotu->save($param)) {
            /*返回错误码*/
            $this->error = ResponseCode::SUCCESS;
        } else {
            $this->error = ResponseCode::SAVE_ERROR;
            return [];
        }

        return [
            'id' => $lunbotu->id
        ];
    }
}