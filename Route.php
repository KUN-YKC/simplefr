<?php
/**
 * 路由解析
 * @author kun
 */
namespace SimpleFr;

class Route
{
    /**
     * 解析路由
     */
    static public function parseUrl()
    {
        $requestUri = getenv('REQUEST_URI');
    }
}
