<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if (!$user) { echo "No user found"; exit; }
$task = \App\Models\Task::where('created_by', $user->id)->first();
if (!$task) {
    echo "No task found\n";
    exit;
}

$request = new \Illuminate\Http\Request();
$request->merge([
    'title' => 'Test Subtask',
    'description' => 'Test Desc',
    'user_id' => $user->id
]);

try {
    $service = app(\App\Services\SubTaskService::class);
    $data = $service->createSubtask($task->id, $request);
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}