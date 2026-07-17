<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('agent.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user->role === 'agent';
});

Broadcast::channel('team-leader', function ($user) {
    return $user->role === 'team_leader';
});
