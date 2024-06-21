<?php

namespace Dcodegroup\ActivityLog\Http\Controllers\API;

use Dcodegroup\ActivityLog\Http\Services\ActivityLogService;
use Dcodegroup\ActivityLog\Models\ActivityLog;
use Illuminate\Routing\Controller;

class DeleteCommentController extends Controller
{
    public function __construct(protected ActivityLogService $service) {}

    public function __invoke(ActivityLog $comment)
    {
        $model = $comment->activitiable()->first();
        $comment->delete();

        return $this->service->getActivityLogs($model);

    }
}
