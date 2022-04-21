<?php

namespace App\Http\Helpers;

use App\Models\ActivityLog as ActivityLogModel;
use Illuminate\Support\Arr;

class ActivityLog
{
    // Create activity log
    public static function createActivityLog($log_name, $description, $subject, $subject_id, $request, $model = null, $modelChanges = null)
    {
        ActivityLogModel::create([
            "log_name" => $log_name,
            "description" => $description,
            "subject_type" => $subject,
            "subject_id" => $subject_id,
            "causer_type" => auth()->user() ? "users" : null,
            "causer_id" => auth()->user() ? auth()->user()->id : null,
            "request" => array(
                'ip' => $request->ip(),
                'method' => $request->url(),
                'url' => $request->method(),
                'inputs' => $request->all(),
                'headers' => $request->header(),
            ),
            "properties" => array('changes' => self::properties($model, $modelChanges)) //changes:[{key:email,value:abc,old:null,status:new},{key:cellphone,value:1234,old:123,status:update}]
        ]);
    }

    //Format activity properties
    public static function properties($model, $modelChanges): array
    {
        $changes = [];

        if ($modelChanges) {
            $modelChanges = Arr::except($modelChanges, ['updated_at', 'password']);

            $keys = array_keys($modelChanges);

            for ($i = 0; $i < count($modelChanges); $i++) {
                $change = array(
                    'key' => $keys[$i],
                    'value' => $modelChanges[$keys[$i]],
                    'old' => $model[$keys[$i]],
                    'status' => 'update'
                );

                array_push($changes, $change);
            }

            return $changes;
        }

        if ($model) {
            $model = Arr::except($model, ['created_at', 'updated_at', 'password']);

            $keys = array_keys($model);

            for ($i = 0; $i < count($model); $i++) {
                $change = array(
                    'key' => $keys[$i],
                    'value' => $model[$keys[$i]],
                    'old' => null,
                    'status' => 'new'
                );

                array_push($changes, $change);
            }

            return $changes;
        }

        return $changes;
    }
}
