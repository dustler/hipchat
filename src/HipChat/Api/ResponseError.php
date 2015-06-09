<?php

namespace HipChat\Api;

use HipChat\Exception\Exception;

trait ResponseError
{
    public function checkError($response)
    {
        if (isset($response->error)) {
            $err = $response->error;
            throw new Exception($err->code, $err->message . ' (' . $err->type . ')',
                // TODO $url
                '');
        }
    }
}
