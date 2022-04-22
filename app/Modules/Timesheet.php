<?php

namespace App\Modules;

use App\Models\Exchange;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class Timesheet extends Auxiliary
{
    /**
     * Соответствия номеров и дней недели
     */
    const WEEKDAYS = [
        0 => 'Воскресенье',
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота',
    ];

    /**
     * Получить имя и отчество преподавателя
     * Если преподавателей с такой фамилией несколько, то выдает их список
     *
     * @param null $piece
     * @return string
     */
    public static function getTeacherName($piece = null)
    {
        $teacher = Teacher::query()->where('name', 'like', '%'.$piece.'%')->get();

        if ($teacher->isNotEmpty()) {
            if (count($teacher) > 1) {
                $answer = self::EMOJI_CLASSIC."Я нашёл несколько преподавателей:\n\n";
                foreach($teacher as $oneTeacher) {
                    $answer .= $oneTeacher->name."\n- ".$oneTeacher->position.((!is_null($oneTeacher->email))?"\nEmail: {$oneTeacher->email}":'')."\n\n";
                }

                return $answer;
            }

            return self::EMOJI_CLASSIC."Я нашёл:\n\n".$teacher->first()->name."\n- ".$teacher->first()->position.((!is_null($teacher->first()->email))?"\nEmail: {$teacher->first()->email}":'');
        }

        return self::EMOJI_CLASSIC."Извините. Я не нашёл такого преподавателя.";
    }

    /**
     * Получить расписание звонков
     *
     * @return string
     */
    public static function getTimes()
    {
        return self::EMOJI_CLASSIC."Расписание звонков:\n\n1 пара: 9:00 - 10:35\n2 пара: 11:05 - 12:40\n3 пара: 13:10 - 14:45\n4 пара: 14:55 - 16:35";
    }

    /**
     * Получить замены для группы
     *
     * @param string $groupNumber
     * @return string
     */
    public static function getExchanges(string $groupNumber)
    {
        $group = Group::query()->where('number', $groupNumber)->get()->first();

        if ($group) {
            $exchanges = $group->exchanges->where('date', $date = Exchange::all()->max('date'));

            if ($exchanges->isEmpty()) {
                return self::EMOJI_CLASSIC."Извините, но для вашей группы нет замен на последнюю загруженную дату.";
            }

            $answer = self::EMOJI_CLASSIC."Расписание группы #{$group->number} на " . date_create($date)->format('d.m.Y') . "<br><br>";

            foreach($exchanges as $exchange) {
                $answer .= "{$exchange->order}. {$exchange->title} (".(($exchange->title=='Физкультура')?'спортзал':$exchange->cab).")<br>";
            }

            return $answer;
        }

        return self::EMOJI_CLASSIC."Извините, но я не смог найти указанную группу.";
    }

    /**
     * Получить расписание на всю неделю
     *
     * @param string $groupNumber
     * @return string
     */
    public static function getSchedule(string $groupNumber)
    {
        $group = Group::query()->where('number', $groupNumber)->get()->first();

        if ($group) {
            $actualSchedule = Schedule::all()->last();
            $lessons = Lesson::with('subject')
                ->where('schedule_id', $actualSchedule->id)
                ->where('group_id', $group->id)
                ->get();
            $answer = self::EMOJI_CLASSIC."Расписание для группы {$group->number} на неделю:\n\n";

            for ($i = 1; $i < 7; $i++) {
                $tmp = $lessons->where('weekday', self::WEEKDAYS[$i])->sortBy('order');

                if ($tmp->isNotEmpty()) {
                    $answer .= self::WEEKDAYS[$i].":\n";

                    foreach($tmp as $les) {
                        $answer .= $les->order . ". " . $les->subject->normal_name . "\n";
                    }

                    $answer .= "\n";
                }
            }

            return $answer;
        }

        return self::EMOJI_CLASSIC."Извините, но я не смог найти указанную группу.";
    }

    /**
     * Получить массив постоянного расписания на день
     *
     * @param int $schedule
     * @param int $group
     * @param string $weekday
     * @param string $even
     * @return array
     */
    public static function getRegularDay(int $schedule, int $group, string $weekday, string $even)
    {
        return Lesson::with('subject')
            ->where('schedule_id', $schedule)
            ->where('group_id', $group)
            ->where('weekday', $weekday)
            ->whereIn('even', ['nvm', $even])
            ->get()
            ->each(function($item) {
                $item->title = $item->subject->normal_name.(!is_null($item->cab) ? (' ('.$item->cab.')') : '');
            })
            ->pluck('title', 'order')
            ->toArray();
    }

    /**
     * Получить массив замен
     *
     * @param int $group
     * @param string $date
     * @return array
     */
    public static function getChangeDay(int $group, string $date)
    {
        return Exchange::with(['teacher'])
            ->where('group_id', $group)
            ->where('date', $date)
            ->get()
            ->each(function($item) {
                $item->title = 'ЗАМЕНА! '.((!is_null($item->title)) ? $item->title : 'Отмена пары').(!is_null($item->cab) ? (' ('.$item->cab.')') : '');
            })
            ->pluck('title', 'order')
            ->toArray();
    }

    /**
     * Получить расписание на следующий актуальный день
     *
     * @param string $groupNumber
     * @return string
     */
    public static function getNextDay(string $groupNumber)
    {
        $group = Group::query()->where('number', $groupNumber)->get()->first();

        if ($group) {
            $actualSchedule = Schedule::all()->last();
            $date = self::getDate();

            $actual = self::getChangeDay($group->id, $date->format('Y-m-d')) + self::getRegularDay($actualSchedule->id, $group->id, $date->format('w'), (($date->format('W') % 2) == 0) ? "even" : "odd");
            ksort($actual);

            $answer = self::EMOJI_CLASSIC."Расписание группы {$groupNumber} на ".$date->format('d.m.Y').":\n";
            foreach($actual as $ord => $lesson) {
                $answer .= $ord . '. ' . $lesson . "\n";
            }

            return $answer;
        }

        return self::EMOJI_CLASSIC."Извините, но я не смог найти указанную группу.";
    }

    /**
     * Определяет текущую дату
     *
     * @param array $args
     * @return DateTime|false|mixed
     */
    public static function getDate($args = [])
    {
        date_default_timezone_set("Europe/Moscow");
        $today = date_create();
        $turnArgs = array(
            "послезавтра" => date_modify(date_create(), "+2 day"),
            "завтра" => date_modify(date_create(), "+1 day"),
            "сегодня" => date_create(),
            "вчера" => date_modify(date_create(), "-1 day")
        );

        if (count($args)) {
            foreach($args as $arg) {
                if (array_key_exists($arg, $turnArgs)) {
                    return $turnArgs[$arg];
                }
            }
        }

        if ($today->format('H') >= 15) {
            $day = date_modify($today, "+1 day");
        }

        if (isset($day) && ($day->format('w') == 0)) {
            $day = date_modify($day, "+1 day");
        }

        if (!isset($day) && ($today->format('w') == 0)) {
            $day = date_modify($today, "+1 day");
        }

        return $day ?? $today;
    }

    /**
     * Включить рассылку
     * @param $uid
     * @param $groupNumber
     * @return string
     */
    public static function setScheduleMailing($uid, $groupNumber)
    {
        if (!($group = Group::query()->where('number', $groupNumber)->get()->first())) {
            return self::EMOJI_CLASSIC . "Извините. Я не нашёл группу \"{$groupNumber}\" среди групп колледжа. Проверьте правильность ввода.";
        }

        Detail::getStudentDB($uid);
        Student::query()->where('id', $uid)->update([
            'group_id' => $group->id,
            'get_mailing' => true,
        ]);

        return self::EMOJI_CLASSIC."Поздравляю! Теперь я буду автоматически присылать Вам расписание с заменами для группы {$groupNumber}.";
    }

    /**
     * Отключить рассылку
     * @param $uid
     * @return string
     */
    public static function cancelScheduleMailing($uid)
    {
        Detail::getStudentDB($uid);
        Student::query()->where('id', $uid)->update([
            'get_mailing' => false,
        ]);

        return self::EMOJI_CLASSIC."Для Вашей страницы отключена рассылка изменений.";
    }

    /**
     * Получить расписание группы для отправки замен
     * @param Group $group
     * @param Collection $lessons
     * @param Collection $exchanges
     * @param string $date
     * @return string
     */
    public static function getScheduleForChanges(Group $group, Collection $lessons, Collection $exchanges, string $date)
    {
        $groupLessons = $lessons->where('group_id', $group->id)->pluck('title', 'order')->toArray();
        $groupExchanges = $exchanges->where('group_id', $group->id)->pluck('title', 'order')->toArray();
        $result = $groupExchanges + $groupLessons;
        ksort($result);
        $answer = self::EMOJI_CLASSIC."Расписание группы {$group->number} на {$date}:\n";

        foreach ($result as $order => $lesson) {
            $answer .= $order . '. ' . $lesson . "\n";
        }

        return $answer;
    }

    public static function sendChanges()
    {
        $students = Student::query()
            ->where('get_mailing', true)
            ->get();

        $groups = Group::all();

        $date = self::getDate();

        $evenly = (($date->format('W') % 2) == 0) ? "even" : "odd";

        $lessons = Lesson::with('subject')
            ->where('schedule_id', Schedule::all()->last()->id)
            ->where('weekday', $date->format('w'))
            ->whereIn('even', ['nvm', $evenly])
            ->get()
            ->each(function($item) {
                $item->title = $item->subject->normal_name.(!is_null($item->cab) ? (' ('.$item->cab.')') : '');
            });
        $exchanges = Exchange::with('teacher')
            ->where('date', $date->format('Y-m-d'))
            ->get()
            ->each(function($item) {
                $item->title = 'ЗАМЕНА! '.((!is_null($item->title)) ? $item->title : 'Отмена пары').(!is_null($item->cab) ? (' ('.$item->cab.')') : '');
            });

        foreach ($groups as $group) {
            $groupStudents = $students->where('group_id', $group->id)->map->id->implode(",");

            if ($groupStudents != "") {
                vkApi::sendManyMessages($groupStudents, self::getScheduleForChanges($group, $lessons, $exchanges, $date->format('d.m.Y')));
            }
        }

        $completeMessage = "Изменения в расписании были разосланы!\n\nКоличество пользователей: ".count($students);//.". Количество чатов: ".count($chats);
        vkApi::sendMessage(3872987, $completeMessage);
    }
}
