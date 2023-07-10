<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Setting;

use App\Controllers\BaseController;
use App\Models\Setting;

final class RegController extends BaseController
{
    public static $update_field = [
        'reg_mode',
        'reg_email_verify',
        'email_verify_ttl',
        'email_verify_ip_limit',
        'email_verify_email_limit',
        'sign_up_for_daily_report',
        'enable_reg_im',
        'enable_reg_new_shop',
        'random_group',
        'min_port',
        'max_port',
        'sign_up_for_free_traffic',
        'free_user_reset_day',
        'free_user_reset_bandwidth',
        'sign_up_for_free_time',
        'sign_up_for_class',
        'sign_up_for_class_time',
        'sign_up_for_method',
        'sign_up_for_invitation_codes',
        'connection_device_limit',
        'connection_rate_limit',
        'reg_forbidden_ip',
        'reg_forbidden_port',
    ];

    public function reg($request, $response, $args)
    {
        $settings = [];
        $settings_raw = Setting::get(['item', 'value', 'type']);

        foreach ($settings_raw as $setting) {
            if ($setting->type === 'bool') {
                $settings[$setting->item] = (bool) $setting->value;
            } else {
                $settings[$setting->item] = (string) $setting->value;
            }
        }

        return $response->write(
            $this->view()
                ->assign('update_field', self::$update_field)
                ->assign('settings', $settings)
                ->fetch('admin/setting/reg.tpl')
        );
    }

    public function saveReg($request, $response, $args)
    {
        $list = self::$update_field;

        foreach ($list as $item) {
            $setting = Setting::where('item', '=', $item)->first();

            if ($setting->type === 'array') {
                $setting->value = \json_encode($request->getParam("${item}"));
            } else {
                $setting->value = $request->getParam("${item}");
            }

            if (! $setting->save()) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => "保存 ${item} 时出错",
                ]);
            }
        }

        return $response->withJson([
            'ret' => 1,
            'msg' => '保存成功',
        ]);
    }
}
