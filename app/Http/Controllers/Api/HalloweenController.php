<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\vkApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class HalloweenController extends Controller
{
    public function first(Request $request)
    {
        switch ($request->input('type')) {
            case 'confirmation':
                $this->cbResponse(Config::get('vk.first.confirmation'));
                break;
            case 'message_new':
                $data = $request->input('object')['message'];
//                $data = json_decode(file_get_contents("test.json"), true);
//                $data = $data['object']['message'];

                $message = explode(' ', $data["text"]);
                $currentCommand = mb_strtolower($message[0], "utf8");

                if ('фредди' === $currentCommand) {
                    $this->firstManager($data, $message);
                }

                $this->cbResponse('ok');
                break;
            default:
                $this->cbResponse('ok');
                break;
        }
    }

    protected function cbResponse(string $answer)
    {
        echo $answer;
        exit;
    }

    protected function firstManager($data, $messageArray)
    {
        vkApi::sendMessageBy($data['peer_id'], 'Я тебя разрежу пополам.', Config::get('vk.first.token'), Config::get('vk.first.version'));
    }
}
