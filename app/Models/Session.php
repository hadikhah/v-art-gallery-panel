<?php
// app/Models/Session.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Session extends Model
{
    protected $table = 'sessions';

    protected $casts = [
        'last_activity' => 'datetime',
        'payload' => 'array',
    ];

    public function getDeviceTypeAttribute()
    {
        $userAgent = $this->user_agent;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($userAgent))) {
            return 'tablet';
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($userAgent))) {
            return 'mobile';
        }

        return 'desktop';
    }

    public function getBrowserAttribute()
    {
        $userAgent = $this->user_agent;

        if (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident/7') !== false) {
            return 'Internet Explorer';
        }

        return 'Other';
    }

    public function getIdAttribute()
    {
        return $this->attributes['id'];
    }

    public static function mapSession($session, $index)
    {

        return [
            'id' => $session->getIdAttribute(),
            'ip_address' => $session->ip_address,
            'is_current_device' => $session->id === session()->getId(),
            'last_activity' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            'device_type' => $session->device_type,
            'browser' => $session->browser,
            'user_agent' => $session->user_agent,
        ];
    }
}
