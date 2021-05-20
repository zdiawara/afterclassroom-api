<?php

namespace App\Http\Actions\Teacher;

use App\Teacher;
use Illuminate\Support\Facades\DB;


class FindTeacher
{
    public function byUsername(string $username)
    {
        return Teacher::whereHas('user', function ($query) use ($username) {
            $query->where(DB::raw('lower(users.username)'), strtolower($username));
        })->firstOrFail();
    }
}
