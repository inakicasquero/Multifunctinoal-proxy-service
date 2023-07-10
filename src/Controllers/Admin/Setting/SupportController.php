<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Setting;

use App\Controllers\BaseController;
use App\Models\Setting;

final class SupportController extends BaseController
{
    public static $update_field = [
        'live_chat',
        'tawk_id',
        'crisp_id',
        'livechat_id',
        'mylivechat_id',
        // Admin Contact
        'enable_admin_contact',
        'admin_contact1',
        'admin_contact2',
        'admin_contact3',
    ];

    public function support($request, $response, $args)
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
                ->fetch('admin/setting/support.tpl')
        );
    }

    public function saveSupport($request, $response, $args)
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
