<?php

namespace Dcodegroup\ActivityLog\Http\Controllers\API;

use Dcodegroup\ActivityLog\Http\Requests\ExistingRequest;
use Dcodegroup\ActivityLog\Models\ActivityLog;
use Dcodegroup\ActivityLog\Resources\ActivityLogCollection;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function __invoke(ExistingRequest $request)
    {
        $modelClass = $request->input('modelClass');
        $modelId = $request->input('modelId');
        $model = $modelClass::find($modelId);
        if ($request->filled('comment')) {
            resolve(config('activity-log.activity_log_model'))->query()->create([
                'activitiable_type' => $modelClass,
                'activitiable_id' => $modelId,
                'type' => ActivityLog::TYPE_COMMENT,
                'title' => 'left a comment.',
                'description' => $request->input('comment'),
            ]);
        }

        $communication = config('activity-log.communication_log_relationship');

        return new ActivityLogCollection($model->activityLogs()
            ->with([
                config('activity-log.user_relationship'),
                $communication,
                "$communication.reads",
            ])
            ->orderByDesc('created_at')->get());
    }
}
