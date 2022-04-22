<?php

namespace App\Modules;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class vkApi
{
    /**
     * Строка для подстановки в вызов API методов
     */
    const VK_API_METHOD = 'https://api.vk.com/method/';

    /**
     * Получить информацию о пользователе
     *
     * @param $uid
     * @param string $fields
     * @return string
     */
    public static function userInfo($uid, $fields = "") {
        $params = http_build_query(array(
            'user_id' => $uid,
            'fields' => $fields,
            'access_token' => Config::get('vk.token'),
            'v' => Config::get('vk.version')
        ));

        return self::VK_API_METHOD . "users.get?" . $params;
    }

    /**
     * Получить информацию о беседе
     *
     * @param $pid
     * @return string
     */
    public static function convInfo($pid) {
        $params = http_build_query(array(
            'peer_id' => $pid,
            'access_token' => Config::get('vk.token'),
            'v' => Config::get('vk.version')
        ));

        return self::VK_API_METHOD . "messages.getConversationMembers?" . $params;
    }

    /**
     * Получение сервера для загрузки изображений
     * @param $album
     * @param $group
     * @param $token
     * @param $version
     * @return false|string
     */
    public static function getUploadSever($album, $group, $token, $version)
    {
        $params = http_build_query([
            'album_id' => $album,
            'group_id' => $group,
            'access_token' => $token,
            'v' => $version,
        ]);

        return file_get_contents(self::VK_API_METHOD . 'photos.getUploadServer?' . $params);
    }

    /**
     * @param $group
     * @param $album
     * @param $image
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function uploadImage($group, $album, $image)
    {
        $uploadUrl = self::getUploadSever($album, $group);

        $response = Http::post($uploadUrl, [
            'file1' => Storage::get($image),
        ]);

        return file_get_contents(json_decode($response->body));
    }

    public static function saveImage($group, $album, $image)
    {
        $params = http_build_query(self::uploadImage($group, $album, $image));

        return self::VK_API_METHOD . 'photos.save' . $params;
    }

    /**
     * Отправить сообщение
     *
     * @param $id
     * @param $message
     * @param int $mentions
     * @param string $attachments
     * @param string $keyboard
     * @return false|string
     */
    public static function sendMessage($id, $message, $mentions = 1, $attachments = "", $keyboard = "") {
        return file_get_contents('https://api.vk.com/method/messages.send', false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query(array(
                    'peer_id' => $id,
                    'message' => $message,
                    'attachment' => $attachments,
                    'keyboard' => $keyboard,
                    'disable_mentions' => $mentions,
                    'access_token' => Config::get('vk.token'),
                    'v' => Config::get('vk.version')
                ))
            )
        )));
    }

    /**
     * Отправить много сообщений
     *
     * @param $ids
     * @param $message
     * @param int $mentions
     * @param string $attachments
     * @return false|string
     */
    public static function sendManyMessages($ids, $message, $mentions = 0, $attachments = "") { # отправить сообщение пользователям
        return file_get_contents('https://api.vk.com/method/messages.send', false, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query(array(
                    'user_ids' => $ids,
                    'message' => $message,
                    'attachment' => $attachments,
                    'disable_mentions' => $mentions,
                    'access_token' => Config::get('vk.token'),
                    'v' => Config::get('vk.version')
                ))
            )
        )));
    }

    /**
     * Задать кнопку с текстом
     *
     * @param $text
     * @param $color
     * @param null $payload
     * @return array
     */
    public static function buttonText($text, $color, $payload = null) {
        return ['text', $payload, $text, $color];
    }

    /**
     * Отправить кнопку
     *
     * @param $id
     * @param $message
     * @param array $buttons
     * @param false $inline
     * @param false $one_time
     * @param array $params
     */
    public static function sendButton($id, $message, $buttons = [], $inline = false, $one_time = False, $params = []) {
        $keyboard = [];
        $i = 0;
        foreach ($buttons as $button_str) {
            $j = 0;
            foreach ($button_str as $button) {
                $keyboard[$i][$j]["action"]["type"] = $button[0];
                if ($button[1] != null)
                    $keyboard[$i][$j]["action"]["payload"] = json_encode($button[1], JSON_UNESCAPED_UNICODE);
                switch ($button[0]) {
                    case 'text': {
                        $color = self::replaceColor($button[3]);
                        $keyboard[$i][$j]["color"] = $color;
                        $keyboard[$i][$j]["action"]["label"] = $button[2];
                        break;
                    }
                }
                $j++;
            }
            $i++;
        }
        $keyboard = ["one_time" => $one_time, "buttons" => $keyboard, 'inline' => $inline];
        $keyboard = json_encode($keyboard, JSON_UNESCAPED_UNICODE);
        // $message = $this->placeholders($id, $message);
        self::sendMessage($id, $message, 1, "", $keyboard);
    }

    /**
     * Заменить цвет
     *
     * @param $color
     * @return string
     */
    public static function replaceColor($color) {
        $colors = array(
            "red" => "negative",
            "green" => "positive",
            "white" => "default",
            "blue" => "primary"
        );
        return $colors[$color] ?? $color;
    }

    /**
     * Метод отправки сообщений через указание токена и версии
     * @param $id
     * @param $message
     * @param $token
     * @param $version
     * @param int $mentions
     * @param string $attachments
     * @param string $keyboard
     * @return false|string
     */
    public static function sendMessageBy($id, $message, $token, $version, $mentions = 1, $attachments = "", $keyboard = "") {
        return file_get_contents('https://api.vk.com/method/messages.send', false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query(array(
                    'peer_id' => $id,
                    'message' => $message,
                    'attachment' => $attachments,
                    'keyboard' => $keyboard,
                    'disable_mentions' => $mentions,
                    'access_token' => $token,
                    'v' => $version,
                    'random_id' => '0',
                ))
            )
        )));
    }
}
