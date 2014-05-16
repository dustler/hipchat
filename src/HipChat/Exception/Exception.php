<?php


namespace HipChat\Exception;


class Exception extends \Exception
{
    public function __construct($code, $info, $url) {
        $message = "HipChat API error: code=$code, info=$info, url=$url";
        parent::__construct($message, (int)$code);
    }
}