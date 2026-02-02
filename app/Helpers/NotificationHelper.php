<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Create a notification for a user
     */
    public static function create(User $user, string $type, string $title, string $message, ?string $icon = null, string $color = 'blue', ?string $link = null, ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'link' => $link,
            'data' => $data,
        ]);
    }

    /**
     * Create notification for multiple users
     */
    public static function createForUsers(array $users, string $type, string $title, string $message, ?string $icon = null, string $color = 'blue', ?string $link = null, ?array $data = null): void
    {
        foreach ($users as $user) {
            if ($user instanceof User) {
                self::create($user, $type, $title, $message, $icon, $color, $link, $data);
            }
        }
    }

    /**
     * Create notification for all admins
     */
    public static function createForAdmins(string $type, string $title, string $message, ?string $icon = null, string $color = 'blue', ?string $link = null, ?array $data = null): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        self::createForUsers($admins->all(), $type, $title, $message, $icon, $color, $link, $data);
    }
}

