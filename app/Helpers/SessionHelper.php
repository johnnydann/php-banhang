<?php
// app/Helpers/SessionHelper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class SessionHelper
{
    /**
     * Lưu đối tượng vào session dưới dạng JSON
     */
    public static function setObjectAsJson($key, $value)
    {
        Session::put($key, json_encode($value));
    }

    /**
     * Lấy đối tượng từ session và chuyển từ JSON sang đối tượng PHP
     *
     * @param string $key Khóa session
     * @param mixed $default Giá trị mặc định trả về nếu khóa không tồn tại
     * @return mixed
     */
    public static function getObjectFromJson($key, $default = null)
    {
        $value = Session::get($key);
        return $value === null ? $default : json_decode($value, true);
    }
}