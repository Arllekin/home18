<?php

namespace App\Http\Controllers\Api;
use App\Jobs\SendEmail;
use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Country;

class UserController
{
    public function store(Request $request)
    {

        $user = $request->validate([
            '*.name' => ['required'],
            '*.email' => ['required', 'distinct', 'unique:users,email'],
            '*.password' => ['required'],
            '*.country_id' => ['required', 'exists:countries,id'],
        ]);

        $counter = count($user);
        for ($q = 0; $q < $counter; $q++) {
            $user[$q]['created_at'] = date("Y-m-d H:i:s");
            $user[$q]['updated_at'] = date("Y-m-d H:i:s");
            $user[$q]['remember_token'] = Str::random(10);
            $user[$q]['password'] = password_hash($user[$q]['password'], PASSWORD_BCRYPT);

            SendEmail::dispatch($user[$q]['email'], "http://localhost/api/login?email={$user[$q]['email']}&remember_token={$user[$q]['remember_token']}");
        }

        User::insert($user);

        return response(null);

    }

    public function login(Request $request)
    {
        $data = [];
        if ($request->has('email') && $request->has('remember_token')) {
            $data = [
                'email' => $request->get('email'),
                'remember_token' => $request->get('remember_token'),
            ];
        } else {
            echo 'Invalid link';
        }

        $user = User::where('email', '=', $data['email'])->get()->first();


        $token = null;
        if ($user) {
            if ($user['remember_token'] !== $data['remember_token']) {
                echo 'Wrong remember token';
            } else {
                $token = $user->createToken('web');
                $user->update(['email_verified_at' => now()]);
            }
        }

        return $token ?? null;
    }

    public function list(Request $request)
    {

        $query = User::query();

        if ($request->has('names')) {

            $names = $request->get('names');
            $query->whereIn('name', $names);
        }

        if ($request->has('emails')) {

            $emails = $request->get('emails');
            $query->whereIn('email', $emails);
        }

        if ($request->has('verify') && $request->get('verify') == true) {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->has('verify') && $request->get('verify') == false) {
            $query->whereNull('email_verified_at');
        }

        if ($request->has('countries')) {
            $countries = $request->get('countries');

            $query->whereHas('country', function (Builder $qb) use ($countries) {
                $qb->whereIn('country_name', $countries);
            });
        }

        return $query->get();
    }



    public function edit( Request $request)
    {
//        !!!Важно. Что-бы работало нужно в модеои Юзеров в свойстве protected $hidden закоментировать пароль и токен.
//        Также ВСЕ значения сущности юзера должны быть внесены в protected $fillable.

//        Вытаскиваем данные из запроса

        $users = $request->validate([
            '*.id' => ['required'],
            '*.name' => ['min:5', 'max:30'],
            '*.email' => ['email'],
            '*.password' => ['min:5', 'max:20'],
            '*.country_id' => ['integer'],
        ]);
//        Первый запрос к базе - вытаскиваю всех пользователей массивом. Не придумал как сразу вытащить нужных

        $data = User::all()->toArray();

//        Создаю массив из айдишников юзеров с которыми будем работать

        $counter = count($users);
        $id = [];
        for ($q = 0; $q < $counter; $q++) {

            $id[] = $users[$q]['id'];
        }

//        Из переменной где у меня массив всех юзеров вытаскиваю тех с кем будем работать. Это юзеры которых я буду
//        менять в моменте до изменений.

        $default = [];
        $counter = count($data);
        for ($q = 0; $q < $counter; $q++) {
            if(in_array($data[$q]['id'], $id)) {

                $default[$data[$q]['id']] = $data[$q];
            }
        }

//        Создаю сущности измененных юзеров. Принцип простой - если в запросе передаётся ключ с каким-то из параметров
//        Юзера - беру информацию из него. Если нет - беру информацию из сущности юзера до изменения.

        $result = [];
        $counter = count($users);
        for ($q = 0; $q < $counter; $q++) {

            if (isset($users[$q]['password'])) {
                $hash = password_hash($users[$q]['password'], PASSWORD_BCRYPT);
            }

            $result[] = ['id' => $users[$q]['id'],
                        'name' => $users[$q]['name'] ?? $default[$users[$q]['id']]['name'],
                        'email' => $users[$q]['email'] ?? $default[$users[$q]['id']]['email'],
                        'email_verified_at' => $default[$users[$q]['id']]['email_verified_at'],
                        'password' => $hash ?? $default[$users[$q]['id']]['password'],
                        'remember_token' => $default[$users[$q]['id']]['remember_token'],
                        'created_at' => $default[$users[$q]['id']]['created_at'],
                        'updated_at' => $default[$users[$q]['id']]['updated_at'],
                        'country_id' => $users[$q]['country_id'] ?? $default[$users[$q]['id']]['country_id'],
            ];
        }

//        Второй запрос к базе - удаляю юзеров  с которыми работал.

        User::query()->whereIn('id', $id)->delete();

//        Третий запрос к базе - вношу в базу обновлённы юзеров.

        User::insert($result);
    }


    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $users = User::query()->whereIn('id', $id)->get();

        foreach ($users as $user) {
            if (Auth::id() !== $user->id) {
                return response(null, '403 Unauthorized');
            }
        }

        User::query()->whereIn('id', $id)->delete();
    }

}
