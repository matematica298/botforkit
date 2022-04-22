<?php

namespace App\Http\Controllers\Api;

use App\Models\Halloween\Character;
use App\Modules\Halloween;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Auxiliary;
use App\Modules\Detail;
use App\Modules\vkApi;
use App\Modules\Timesheet;
use Illuminate\Support\Facades\Config;

class BotController extends Controller
{
    /**
     * Доступные команды для ЛС
     */
    const AVAILABLE_PRIVATE = [
        'привет',
        'ио',
        'звонки',
        'расписание',
        'замены',
        'инфо',
        'рассылка',
    ];

    /**
     * Доступные имена для чатов
     */
    const AVAILABLE_NAMES = [
        'сырок',
        'сырок,'
    ];

    /**
     * Основной метод для работы с ботом через API
     * @param Request $request
     */
    public function start(Request $request): void
    {
        switch ($request->input('type')) {
            case 'confirmation':
                $this->cbResponse(Config::get('vk.confirmation'));
                break;
            case 'message_new':
                $data = $request->input('object'); # array

                $message = explode(' ', $data["text"]); # ['расписание', '294']
                $currentCommand = mb_strtolower($message[0], "utf8");

                if (($data["from_id"] === $data["peer_id"])) { # если это ЛС
                    if (in_array($currentCommand, self::AVAILABLE_PRIVATE) || ($message[0] = $this->checkForMistakes($currentCommand, self::AVAILABLE_PRIVATE))) {
                        $this->privateManager($data, $message);
                    }
                } elseif ($data['peer_id'] == '2000000002') {
                    $this->halloweenManager($data, $message);
                } elseif (in_array($currentCommand, self::AVAILABLE_NAMES)) {
                    $this->chatManager($data, $message);
                }

                $this->cbResponse('ok');
                break;
            default:
                $this->cbResponse('ok');
                break;
        }
    }

    /**
     * Для создания ответа
     * @param string $answer
     */
    protected function cbResponse(string $answer)
    {
        echo $answer;

        exit;
    }

    /**
     * Менеджер ответов для ЛС
     * @param $data
     * @param $messageArray
     */
    protected function privateManager($data, $messageArray)
    {
        switch (mb_strtolower($messageArray[0])) {
            case 'замены':
                vkApi::sendMessage($data["from_id"], (isset($messageArray[1]) ? Timesheet::getExchanges($messageArray[1]) : Auxiliary::EMOJI_CLASSIC.'Пожалуйста, укажите группу.'));
                break;
            case 'привет':
                vkApi::sendMessage($data["from_id"], Auxiliary::EMOJI_CLASSIC . "Привет! Меня зовут Сырок и я чат-бот Колледжа информационных технологий.\n\nМогу я чем-нибудь тебе помочь?");
                break;
            case 'ио':
                vkApi::sendMessage($data["from_id"], Timesheet::getTeacherName(isset($messageArray[1]) ? Auxiliary::skz_ucfirst(strtolower($messageArray[1])) : null));
                break;
            case 'звонки':
                vkApi::sendMessage($data["from_id"], Timesheet::getTimes());
                break;
            case 'рассылка':
                if (isset($messageArray[1])) {
                    if ('отмена' === $messageArray[1]) {
                        vkApi::sendMessage($data['peer_id'], Timesheet::cancelScheduleMailing($data['from_id']));
                    } else {
                        vkApi::sendMessage($data['peer_id'], Timesheet::setScheduleMailing($data['from_id'], $messageArray[1]));
                    }
                }
                break;
            case 'расписание':
                if (isset($messageArray[1])) {
                    if (isset($messageArray[2]) && ($messageArray[2] === 'полное')) {
                        vkApi::sendMessage($data["from_id"], Timesheet::getSchedule($messageArray[1]));
                    } else {
                        vkApi::sendMessage($data["from_id"], Timesheet::getNextDay($messageArray[1]));
                    }
                } else {
                    vkApi::sendMessage($data["from_id"], Auxiliary::EMOJI_CLASSIC.'Пожалуйста, укажите группу.');
                }
                break;
            case 'инфо':
                vkApi::sendMessage($data['peer_id'], Detail::infoManager(array_slice($messageArray, 1), $data['from_id']));
                break;
        }
    }

    /**
     * Менеджер ответов для чатов
     * @param $data
     * @param $messageArray
     */
    protected function chatManager($data, $messageArray)
    {
        if (isset($messageArray[1])) {
            switch (mb_strtolower($messageArray[1])) {
                case 'звонки':
                    vkApi::sendMessage($data["peer_id"], Timesheet::getTimes());
                    break;
                case 'ио':
                    vkApi::sendMessage($data["peer_id"], Timesheet::getTeacherName(isset($messageArray[2]) ? Auxiliary::skz_ucfirst(strtolower($messageArray[2])) : null));
                    break;
                case 'замены':
                    vkApi::sendMessage($data["peer_id"], (isset($messageArray[2]) ? Timesheet::getExchanges($messageArray[2]) : Auxiliary::EMOJI_CLASSIC.'Пожалуйста, укажите группу.'));
                    break;
                case 'расписание':
                    if (isset($messageArray[2])) {
                        if (isset($messageArray[3]) && ($messageArray[3] === 'полное')) {
                            vkApi::sendMessage($data["peer_id"], Timesheet::getSchedule($messageArray[2]));
                        } else {
                            vkApi::sendMessage($data["peer_id"], Timesheet::getNextDay($messageArray[2]));
                        }
                    } else {
                        vkApi::sendMessage($data["peer_id"], Auxiliary::EMOJI_CLASSIC.'Пожалуйста, укажите группу.');
                    }
                    break;
                case '+':
                case 'одобряю':
                case 'согласен':
                case 'f':
                    if (count($data['fwd_messages'])) {
                        vkApi::sendMessage($data['peer_id'], Detail::changeRep($data, true));
                    }
                    break;
                case '-':
                case 'осуждаю':
                    if (count($data['fwd_messages'])) {
                        vkApi::sendMessage($data['peer_id'], Detail::changeRep($data, false));
                    }
                    break;
                case 'инфо':
                    vkApi::sendMessage($data['peer_id'], Detail::infoManager(array_slice($messageArray, 2), $data['from_id']));
                    break;
            }
        }
    }

    /**
     * @param $data
     * @param $messageArray
     */
    protected function halloweenManager($data, $messageArray)
    {
        if (isset($data['action'])) {
            if (($data['action']['type'] === 'chat_kick_user')) {
                Character::query()->where('student_id', $data['from_id'])->update([
                    'busy' => false,
                    'student_id' => null,
                ]);
            } elseif (($data['action']['type'] === 'chat_invite_user_by_link') || ($data['action']['type'] === 'chat_invite_user')) {
                $student = Detail::getStudentDB($data['from_id']);

                if (!$student->character) {
                    $character = Character::query()->where('busy', false)->get()->shuffle()->random();
                    $character->busy = true;
                    $character->student_id = $data['from_id'];
                    $character->save();

                    vkApi::sendMessage($data['peer_id'], Halloween::welcomeMessage($character));
                }
            }
        }
    }

    /**
     * Проверить команду на ошибки
     * @param string $command
     * @param array $commandList
     * @return false|mixed
     */
    protected function checkForMistakes(string $command, array $commandList)
    {
        foreach ($commandList as $possibleCommand) {
            if ((levenshtein($command, $possibleCommand) / strlen($possibleCommand)) < 0.25) {
                return $possibleCommand;
            }
        }

        return false;
    }
}
