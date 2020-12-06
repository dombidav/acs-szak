<?php


namespace App\Helpers;


use App\Models\Log;

class LogHelper
{
    public static function Error($event){
        self::Save($event, 2);
    }

    public static function Warn($event){
        self::Save($event, 1);
    }

    public static function Notify($event){
        self::Save($event, 0);
    }

    public static function Save($event, $importance){
        $temp = new Log([
            'event' => $event,
            'importance' => $importance
        ]);
        $temp->save();
    }

    public static function Read($number = 0){
        return $number == 0
            ? Log::query()->orderByDesc('created_at')->get()
            : Log::query()->orderByDesc('created_at')->limit($number)->get();
    }
}
