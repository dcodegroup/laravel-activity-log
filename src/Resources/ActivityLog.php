<?php

namespace Dcodegroup\ActivityLog\Resources;

use Dcodegroup\ActivityLog\Models\ActivityLog as ActivityLogModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ActivityLogModel $resource
 */
class ActivityLog extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user' => $this->resource->user?->full_name,
            'description' => $this->resource->description,
            'activitiable_type' => $this->resource->activitiable_type,
            'type' => $this->resource->type,
            'meta' => $this->resource->meta,
            'created_at' => $this->resource->created_at->diffForHumans(),
            'created_at_date' => $this->resource->created_at->format(config('activity-log.date_format')),
            'communication' => $this->getCommunicationLog(),
        ];
    }

    private function getCommunicationLog(): ?array
    {
        if (! $this->resource->communicationLog) {
            return null;
        }

        return [
            'id' => $this->resource->communicationLog->id,
            'to' => $this->resource->communicationLog->to,
            'subject' => $this->resource->communicationLog->subject,
            'content' => $this->resource->communicationLog->content,
        ];
    }
}
