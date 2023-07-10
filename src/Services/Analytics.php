<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Node;
use App\Models\User;
use App\Utils\Tools;

final class Analytics
{
    public function getTotalUser()
    {
        return User::count();
    }

    public function getCheckinUser()
    {
        return User::where('last_check_in_time', '>', 0)->count();
    }

    public function getTodayCheckinUser()
    {
        return User::where('last_check_in_time', '>', strtotime('today'))->count();
    }

    public function getTrafficUsage()
    {
        return Tools::flowAutoShow(User::sum('u') + User::sum('d'));
    }

    public function getTodayTrafficUsage()
    {
        return Tools::flowAutoShow(User::sum('u') + User::sum('d') - User::sum('last_day_t'));
    }

    public function getRawTodayTrafficUsage()
    {
        return User::sum('u') + User::sum('d') - User::sum('last_day_t');
    }

    public function getRawGbTodayTrafficUsage()
    {
        return User::sum('u') + User::sum('d') - User::sum('last_day_t');
    }

    public function getLastTrafficUsage()
    {
        return Tools::flowAutoShow(User::sum('last_day_t'));
    }

    public function getRawLastTrafficUsage()
    {
        return User::sum('last_day_t');
    }

    public function getRawGbLastTrafficUsage()
    {
        return Tools::flowToGB(User::sum('last_day_t'));
    }

    public function getUnusedTrafficUsage()
    {
        return Tools::flowAutoShow(User::sum('transfer_enable') - User::sum('u') - User::sum('d'));
    }

    public function getRawUnusedTrafficUsage()
    {
        return User::sum('transfer_enable') - User::sum('u') - User::sum('d');
    }

    public function getRawGbUnusedTrafficUsage()
    {
        return Tools::flowToGB(User::sum('transfer_enable') - User::sum('u') - User::sum('d'));
    }

    public function getTotalTraffic()
    {
        return Tools::flowAutoShow(User::sum('transfer_enable'));
    }

    public function getRawTotalTraffic()
    {
        return User::sum('transfer_enable');
    }

    public function getRawGbTotalTraffic()
    {
        return Tools::flowToGB(User::sum('transfer_enable'));
    }

    public function getOnlineUser($time)
    {
        $time = \time() - $time;
        return User::where('t', '>', $time)->count();
    }

    public function getUnusedUser()
    {
        return User::where('t', '=', 0)->count();
    }

    public function getTotalNode()
    {
        return Node::count();
    }

    public function getTotalNodes()
    {
        return Node::where('node_heartbeat', '>', 0)->where(
            static function ($query): void {
                $query->Where('sort', '=', 0)
                    ->orWhere('sort', '=', 10)
                    ->orWhere('sort', '=', 11)
                    ->orWhere('sort', '=', 12)
                    ->orWhere('sort', '=', 13)
                    ->orWhere('sort', '=', 14);
            }
        )->count();
    }

    public function getAliveNodes()
    {
        return Node::where(
            static function ($query): void {
                $query->Where('sort', '=', 0)
                    ->orWhere('sort', '=', 10)
                    ->orWhere('sort', '=', 11)
                    ->orWhere('sort', '=', 12)
                    ->orWhere('sort', '=', 13)
                    ->orWhere('sort', '=', 14);
            }
        )->where('node_heartbeat', '>', \time() - 90)->count();
    }
}
