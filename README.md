# Laravel Activity Log

This package provides the standard activity log functionality used in most projects.

## Installation
#### PHP
You can install the package via composer:

```bash
composer require dcodegroup/activity-log
```

Then run the install command.

```bash
php artisan activity-log:install
```

Add the publish command to your composer.json 

```yaml
  "post-update-cmd": [
        ...
        "@php artisan vendor:publish --tag=activity-log-assets --force"
    ]
```

This will publish the configuration file and the migration file.

Run the migrations

```bash
php artisan migrate
```

## User Model

Add the following contract to the `User` model.

```php  

use Dcodegroup\ActivityLog\Contracts\HasActivityUser;

class User extends Authenticatable implements HasActivityUser
{
...

public function getActivityLogUserName(): string
{
    return $this->name;
}
```

#### JS

Include this built file to your layouts:

```html
<script type="text/javascript" src="/vendor/activity-log/index.js" defer></script>
```

Seem to need this in `tailwind.config.js` under spacing: 

```js
spacing: {
    "3xlSpace": "96px",
    "2xlSpace": "64px",
    xlSpace: "32px",
    lgSpace: "24px",
    mdSpace: "16px",
    smSpace: "12px",
    xsSpace: "8px",
    "2xsSpace": "4px",
    "3xsSpace": "2px",
},
```

#### SCSS

There is a new generated file under `public/vendor/activity-log/index.css`. You must use this file in your main scss file

Run the npm build (dev/prod)

```bash
npm run prod
```

## Configuration

Most of configuration has been set the fair defaults. However you can review the configuration file at `config/activity-log.php` and adjust as needed

```php

<?php

use Dcodegroup\ActivityLog\Models\ActivityLog;

return [

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | What middleware should the package apply.
    |
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    |
    | Here you can configure the route paths and route name variables.
    |
    | What should the route path for the activity log be
    | eg 'api/generic/activity-logs'
    |
    | What should the route name for the activity log be
    | eg eg 'api.generic.activity-logs',
    */

    'route_path' => env('LARAVEL_ACTIVITY_LOG_ROUTE_PATH', 'activity-logs'),
    'route_name' => env('LARAVEL_ACTIVITY_LOG_ROUTE_NAME', 'activity-logs'),

    /*
    |--------------------------------------------------------------------------
    | Model and Binding
    |--------------------------------------------------------------------------
    |
    | binding - eg 'activity-logs'
    | model - eg 'ActivityLog'
    |
   */

    'binding' => env('LARAVEL_ACTIVITY_LOG_MODEL_BINDING', 'activity-logs'),
    'model' => ActivityLog::class,

    /*
     |--------------------------------------------------------------------------
     | Formatting
     |--------------------------------------------------------------------------
     |
     | Configuration here is for display configuration
     |
    */

    'datetime_format' => env('LARAVEL_ACTIVITY_LOG_DATETIME_FORMAT', 'd-m-Y H:ia'),
    'date_format' => env('LARAVEL_ACTIVITY_LOG_DATE_FORMAT', 'd.m.Y'),

    /*
     |--------------------------------------------------------------------------
     | Pagination
     |--------------------------------------------------------------------------
     |
     | Configuration here is for pagination
     |
    */

    'default_filter_pagination' => env('LARAVEL_ACTIVITY_LOG_PAGINATION', 50),

    /*
     |--------------------------------------------------------------------------
     | User
     |--------------------------------------------------------------------------
     |
     | Configuration here is for the user model and table
     | eg 'User'
    */

    'user_model' => \App\Models\User::class,
    'user_table' => env('LARAVEL_ACTIVITY_LOG_USERS_TABLE', 'users'),

    /*
     |--------------------------------------------------------------------------
     | Filter Builder
     |--------------------------------------------------------------------------
     |
     | Configuration here is for the filter builder
     | eg 'FilterBuilder class: App\Support\QueryBuilder\Filters\FilterBuilder'
    */

    'filter_builder_path' => env('LARAVEL_ACTIVITY_LOG_FILTER_BUILDER_PATH', ''),

    /*
     |--------------------------------------------------------------------------
     | Events
     |--------------------------------------------------------------------------
     |
     | Configuration here is for the events
     | eg 'open_modal_event' => 'openModal'
    */

    'open_modal_event' => env('LARAVEL_ACTIVITY_LOG_EVENT_OPEN_MODEL', 'openModal'),
    'reload_event' => env('LARAVEL_ACTIVITY_LOG_EVENT_RELOAD', 'getActivities'),
];


```

## Usage

The package provides an endpoints which you can use. See the full list by running
```bash
php artisan route:list --name=activity-log
```

They are

[example.com/activity-logs] Which is where you will form index. This is by default protected auth middleware but you can modify in the configuration. This is where you want to link to in your admin and possibly a new window

## QueryBuilder Filters

Located in
```
src\Support\QueryBuilder\Filters\DateRangeFilter.php
src\Support\QueryBuilder\Filters\TermFilter.php
```

## Traits for activity log model

Located in
```
src\Support\Traits\ActivityLoggable.php
src\Support\Traits\LastModifiedBy.php
```

Using `<activity-log-list>` or `<v-activity-log>` to display activity log list. Pass filter as a slot if filter functionality is needed
```html
      <ActivityLogList :model-id="tender.id" :model-class="tenderModel">
        <v-filter entity="activity-logs" class="flex flex-row-reverse space-x-2 space-x-reverse"> </v-filter>
      </ActivityLogList>
```
