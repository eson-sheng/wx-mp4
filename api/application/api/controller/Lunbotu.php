<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-04-12
 * Time: 20:40
 */

namespace app\api\controller;

use think\Controller;

class Lunbotu extends Controller
{
    public function index ()
    {
        $tu = $this->request->param('tu', FALSE);

        $validate_Lunbotu = new \app\api\validate\Lunbotu();
        return $validate_Lunbotu->get($tu);
    }

    public function add ()
    {
        $title = $this->request->param('title', FALSE);
        $img = $this->request->param('img', FALSE);
        $href = $this->request->param('href', FALSE);

        $validate_Lunbotu = new \app\api\validate\Lunbotu();
        return $validate_Lunbotu->add($title, $img, $href);
    }
}