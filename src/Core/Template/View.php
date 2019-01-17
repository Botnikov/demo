<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 18.01.19
 * Time: 0:20
 */

namespace Core\Template;


use Core\Kernel;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\TextResponse;

class View
{

    /**@var TemplateRenderInterface $renderInterface */
    private static $renderInterface;

    /**
     * @param $path
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return HtmlResponse
     */
    public static function html($path, array $data = [], int $status = 200, array $headers = []): HtmlResponse
    {
        if (!self::$renderInterface) {
            self::$renderInterface = Kernel::boot()->get(TemplateRenderInterface::class);
        }
        $render = self::$renderInterface;
        return new HtmlResponse($render->render($path, $data), $status, $headers);
    }

    /**
     * @param string $text
     * @param int $status
     * @param array $headers
     * @return TextResponse
     */
    public static function text(string $text = '', int $status = 200, array $headers = []): TextResponse
    {
        return new TextResponse($text, $status, $headers);
    }

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param int $params
     * @return JsonResponse
     */
    public static function json($data = [], int $status = 200, array $headers = [], int $params = JsonResponse::DEFAULT_JSON_FLAGS): JsonResponse
    {
        return new JsonResponse($data, $status, $headers, $params);
    }

    /**
     * @param $url
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    public static function redirect($url, int $status = 302, array $headers = []): RedirectResponse
    {

        return new RedirectResponse($url, $status, $headers);
    }



}