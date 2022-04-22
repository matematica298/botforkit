<?php

namespace App\Modules;

use App\Models\Group;
use App\Models\StudentRole as Role;
use App\Models\Student;
use App\Models\RoleStudent as StudentRole;
use Illuminate\Support\Arr;

class Detail extends Auxiliary
{
    /**
     * Получить модель пользователя
     * @param $uid
     * @return mixed
     */
    public static function getStudentDB($uid)
    {
        if (is_null(Student::query()->find($uid))) {
            $userInformation = json_decode(file_get_contents(vkApi::userInfo($uid)));

            Student::query()->create([
                'id' => $uid,
                'name' => $userInformation->response[0]->first_name
            ]);
        }

        return Student::query()->find($uid);
    }

    /**
     * Получить ник студента, если он есть
     * @param $student
     * @return mixed
     */
    public static function getStudentName($student)
    {
        return (!is_null($student->nickname) ? $student->nickname : $student->name);
    }

    /**
     * @param $student
     * @return string
     */
    public static function getStudentInfo($student)
    {
        $answer = "&#128100; [id{$student->id}|" . self::getStudentName($student) . "]";

        if ($student->roles->isNotEmpty()) {
            foreach ($student->roles as $role) {
                $answer .= ', ' . mb_strtolower($role->title);
            }
        }

        $answer .= "\n";

        if (!is_null($student->group_id)) {
            /** @var Group $group */
            $group = Group::query()->find($student->group_id);
            $answer .= "Группа: {$group->number}\n";
        }

        if (!is_null($student->description)) {
            $answer .= $student->description . "\n";
        }

        $answer .= "\n";
        $answer .= "&#128077;&#127995; Репутация: " . $student->reputation;

        return $answer;
    }

    /**
     * @param $data
     * @param bool $increment
     * @return string
     */
    public static function changeRep($data, $increment = true)
    {
        if ($data['from_id'] == $data['fwd_messages'][0]->from_id) {
            return ($increment) ? "Негодяй, не накручивай! Ёще раз увижу и отчислю!" : "Самокритика это конечно хорошо, но всё же не стоит. Отчислять я тебя за это не буду, но надеюсь, что мы поняли друг друга.";
        }

        $student = self::getStudentDB($data['fwd_messages'][0]->from_id);

        $previousRep = $student->rep;
        $student->rep = ($increment) ? ($student->rep + 1) : ($student->rep - 1);
        $student->save();

        $strAnswer = "Репутация пользователя (".self::getStudentName($student).") ".($increment ? "повышена" : "понижена")." (".$previousRep." -> ".$student->rep.").";

        return self::EMOJI_CLASSIC . $strAnswer;
    }

    /**
     * @param Student $student
     * @param string $field
     * @param $value
     */
    public static function setStudentAttribute(Student $student, string $field, $value)
    {
        $student->update([
            $field => $value,
        ]);
    }

    /**
     * @param $args
     * @param $uid
     * @return string
     */
    public static function infoManager($args, $uid)
    {
        $student = self::getStudentDB($uid);

        if (!$args) {
            return self::getStudentInfo($student);
        } else {
            switch ($args[0]) {
                case 'я':
                    if (isset($args[1])) {
                        $role = Role::query()->where('short_title', 'like', $args[1].'%')->get()->first();

                        if (!$role) {
                            return self::EMOJI_CLASSIC . 'Извините, я не нашел такую роль.';
                        }

                        if (!in_array($role->id, $student->roles->pluck('id')->toArray())) {
                            StudentRole::query()->create([
                                'student_id' => $uid,
                                'role_id' => $role->id,
                            ]);
                        }
                    }

                    if (isset($args[2])) {
                        if (!($group = self::getGroupByNumber(Arr::last($args)))) {
                            return self::EMOJI_CLASSIC . 'Извините, я не нашел такую группу.';
                        }

                        self::setStudentAttribute($student, 'group_id', $group->id);
                    }

                    return self::EMOJI_CLASSIC . 'Я запомнил.';
                    break;
                case 'группа':
                    if (isset($args[1])) {
                        if (!($group = self::getGroupByNumber($args[1]))) {
                            return self::EMOJI_CLASSIC . 'Извините, я не нашел такую группу.';
                        }
                        self::setStudentAttribute($student, 'group_id', $group->id);

                        return self::EMOJI_CLASSIC . 'Я запомнил.';
                    }
                    return self::EMOJI_CLASSIC . 'Ваша группа: ' . $student->group->number;
                    break;
                case 'ник':
                    if (isset($args[1])) {
                        if ($args[1] == 'удалить') {
                            if (is_null($student->nickname)) {
                                return self::EMOJI_CLASSIC . 'У вас отсутствует ник.';
                            }
                            self::setStudentAttribute($student, 'nickname', null);

                            return self::EMOJI_CLASSIC . 'Я забуду твой ' . mb_strtolower(self::getRandomElement(self::ADJECTIVES)) . ' ник.';
                        } else {
                            self::setStudentAttribute($student, 'nickname', $args[1]);

                            return self::EMOJI_CLASSIC . 'Я запомню твой ' . mb_strtolower(self::getRandomElement(self::ADJECTIVES)) . ' ник.';
                        }
                    }
                    return self::EMOJI_CLASSIC . 'Ваш ник: ' . $student->nickname;
                    break;
                case 'описание':
                    if (isset($args[1])) {
                        if ($args[1] == 'удалить') {
                            if (is_null($student->description)) {
                                return self::EMOJI_CLASSIC . 'У вас отсутствует описание.';
                            }
                            self::setStudentAttribute($student, 'description', null);
                        } else {
                            self::setStudentAttribute($student, 'description', implode(' ', array_slice($args, 1)));
                            return self::EMOJI_CLASSIC . 'Я запомнил.';
                        }
                    }
                    break;
            }
        }
    }
}
