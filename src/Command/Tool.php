<?php

declare(strict_types=1);

namespace App\Command;

use App\Models\Node;
use App\Models\Setting;
use App\Models\User as ModelsUser;
use App\Utils\GA;
use App\Utils\Hash;
use App\Utils\QQWry;
use App\Utils\Tools;
use Exception;
use Ramsey\Uuid\Uuid;

final class Tool extends Command
{
    public $description = <<<EOL
├─=: php xcat Tool [选项]
│ ├─ initQQWry               - 下载 IP 解析库
│ ├─ setTelegram             - 设置 Telegram 机器人
│ ├─ resetAllSettings        - 使用默认值覆盖设置中心设置
│ ├─ exportAllSettings       - 导出所有设置
│ ├─ importAllSettings       - 导入所有设置
│ ├─ upgradeDatabase         - 升级(如果不存在的话初始化) 数据库
│ ├─ resetNodePassword       - 重置所有节点通讯密钥
│ ├─ getCookie               - 获取指定用户的 Cookie
│ ├─ resetPort               - 重置单个用户端口
│ ├─ createAdmin             - 创建管理员帐号
│ ├─ resetAllPort            - 重置所有用户端口
│ ├─ resetTraffic            - 重置所有用户流量
│ ├─ generateUUID            - 为所有用户生成新的 UUID
│ ├─ generateGa              - 为所有用户生成新的 Ga Secret
│ ├─ setTheme                - 为所有用户设置新的主题

EOL;

    public function boot(): void
    {
        if (count($this->argv) === 2) {
            echo $this->description;
        } else {
            $methodName = $this->argv[2];
            if (method_exists($this, $methodName)) {
                $this->$methodName();
            } else {
                echo '方法不存在.' . PHP_EOL;
            }
        }
    }

    public function initQQWry(): void
    {
        echo '正在下载或更新纯真 IP 数据库...' . PHP_EOL;
        $path = BASE_PATH . '/storage/qqwry.dat';
        $qqwry = file_get_contents('https://cdn.jsdelivr.net/gh/sspanel-uim/qqwry.dat@latest/qqwry.dat');
        if ($qqwry !== '') {
            if (is_file($path)) {
                rename($path, $path . '.bak');
            }
            $fp = fopen($path, 'wb');
            if ($fp) {
                fwrite($fp, $qqwry);
                fclose($fp);
                echo '纯真 IP 数据库下载成功.' . PHP_EOL;
                $iplocation = new QQWry();
                $location = $iplocation->getlocation('8.8.8.8');
                $Userlocation = $location['country'];
                if (iconv('gbk', 'utf-8//IGNORE', $Userlocation) !== '美国') {
                    unlink($path);
                    if (is_file($path . '.bak')) {
                        rename($path . '.bak', $path);
                    }
                }
            } else {
                echo '纯真 IP 数据库保存失败，请检查权限' . PHP_EOL;
            }
        } else {
            echo '纯真 IP 数据库下载失败，请检查下载地址' . PHP_EOL;
        }
    }

    public function setTelegram(): void
    {
        $WebhookUrl = $_ENV['baseUrl'] . '/telegram_callback?token=' . $_ENV['telegram_request_token'];
        $telegram = new \Telegram\Bot\Api($_ENV['telegram_token']);
        $telegram->removeWebhook();
        if ($telegram->setWebhook(['url' => $WebhookUrl])) {
            echo 'Bot @' . $telegram->getMe()->getUsername() . ' 设置成功！' . PHP_EOL;
        } else {
            echo '设置失败！' . PHP_EOL;
        }
    }

    public function resetAllSettings(): void
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            $setting->value = $setting->default;
            $setting->save();
        }

        echo '已使用默认值覆盖所有设置.' . PHP_EOL;
    }

    public function exportAllSettings(): void
    {
        $settings = Setting::all();
        foreach ($settings as $setting) {
            // 因为主键自增所以即便设置为 null 也会在导入时自动分配 id
            // 同时避免多位开发者 pull request 时 settings.json 文件 id 重复所可能导致的冲突
            $setting->id = null;
            // 避免开发者调试配置泄露
            $setting->value = $setting->default;
        }

        $json_settings = \json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents('./config/settings.json', $json_settings);

        echo '已导出所有设置.' . PHP_EOL;
    }

    public function importAllSettings(): void
    {
        $json_settings = file_get_contents('./config/settings.json');
        $settings = \json_decode($json_settings, true);
        $config = [];
        $add_counter = 0;
        $del_counter = 0;

        // 检查新增
        foreach ($settings as $item) {
            $config[] = $item['item'];
            $item_name = $item['item'];
            $query = Setting::where('item', '=', $item['item'])->first();

            if ($query === null) {
                $new_item = new Setting();
                $new_item->id = null;
                $new_item->item = $item['item'];
                $new_item->value = $item['value'];
                $new_item->class = $item['class'];
                $new_item->is_public = $item['is_public'];
                $new_item->type = $item['type'];
                $new_item->default = $item['default'];
                $new_item->mark = $item['mark'];
                $new_item->save();

                echo "添加新设置：${item_name}" . PHP_EOL;
                $add_counter += 1;
            }
        }
        // 检查移除
        $db_settings = Setting::all();
        foreach ($db_settings as $db_setting) {
            if (! \in_array($db_setting->item, $config)) {
                $db_setting->delete();
                $del_counter += 1;
            }
        }

        if ($add_counter !== 0) {
            echo "总计添加了 ${add_counter} 条新设置." . PHP_EOL;
        } else {
            echo '没有任何新设置需要添加.' . PHP_EOL;
        }
        if ($del_counter !== 0) {
            echo "总计移除了 ${del_counter} 条设置." . PHP_EOL;
        }
    }

    public function upgradeDatabase(): void
    {
        $phinx = new \Phinx\Console\PhinxApplication();
        $phinx->run();
    }

    public function resetNodePassword(): void
    {
        $nodes = Node::all();
        foreach ($nodes as $node) {
            $node->password = Tools::genRandomChar(32);
            $node->save();
        }
        echo '已重置所有节点密码.' . PHP_EOL;
    }

    /**
     * 重置用户端口
     */
    public function resetPort(): void
    {
        fwrite(STDOUT, '请输入用户id: ');
        $user = ModelsUser::find(trim(fgets(STDIN)));
        if ($user !== null) {
            $user->port = Tools::getAvPort();
            if ($user->save()) {
                echo '重置成功!';
            }
        } else {
            echo 'not found user.';
        }
    }

    /**
     * 重置所有用户端口
     */
    public function resetAllPort(): void
    {
        $users = ModelsUser::all();
        foreach ($users as $user) {
            $origin_port = $user->port;
            $user->port = Tools::getAvPort();
            echo '$origin_port=' . $origin_port . '&$user->port=' . $user->port . PHP_EOL;
            $user->save();
        }
    }

    /**
     * 重置所有用户流量
     */
    public function resetTraffic(): void
    {
        try {
            ModelsUser::where('enable', 1)->update([
                'd' => 0,
                'u' => 0,
                'last_day_t' => 0,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }
        echo 'reset traffic successful';
    }

    /**
     * 为所有用户生成新的UUID
     */
    public function generateUUID(): void
    {
        $users = ModelsUser::all();
        $current_timestamp = \time();
        foreach ($users as $user) {
            /** @var ModelsUser $user */
            $user->generateUUID($current_timestamp);
        }
        echo 'generate UUID successful';
    }

    /**
     * 二次验证
     */
    public function generateGa(): void
    {
        $users = ModelsUser::all();
        foreach ($users as $user) {
            $ga = new GA();
            $secret = $ga->createSecret();

            $user->ga_token = $secret;
            $user->save();
        }
        echo 'generate Ga Secret successful';
    }

    /**
     * 创建 Admin 账户
     */
    public function createAdmin(): void
    {
        if (count($this->argv) === 3) {
            // ask for input
            fwrite(STDOUT, '(1/3) 请输入管理员邮箱：') . PHP_EOL;
            // get input
            $email = trim(fgets(STDIN));
            if ($email === null) {
                die("必须输入管理员邮箱.\r\n");
            }

            // write input back
            fwrite(STDOUT, '(2/3) 请输入管理员账户密码：') . PHP_EOL;
            $passwd = trim(fgets(STDIN));
            if ($passwd === null) {
                die("必须输入管理员密码.\r\n");
            }

            fwrite(STDOUT, '(3/3) 按 Y 或 y 确认创建：');
            $y = trim(fgets(STDIN));
        } elseif (count($this->argv) === 5) {
            [,,, $email, $passwd] = $this->argv;
            $y = 'y';
        }

        if (strtolower($y) === 'y') {
            $current_timestamp = \time();
            // create admin user
            $configs = Setting::getClass('register');
            // do reg user
            $user = new ModelsUser();
            $user->user_name = 'admin';
            $user->email = $email;
            $user->remark = 'admin';
            $user->pass = Hash::passwordHash($passwd);
            $user->passwd = Tools::genRandomChar(16);
            $user->uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
            $user->port = Tools::getLastPort() + 1;
            $user->t = 0;
            $user->u = 0;
            $user->d = 0;
            $user->transfer_enable = Tools::toGB($configs['sign_up_for_free_traffic']);
            $user->invite_num = $configs['sign_up_for_invitation_codes'];
            $user->ref_by = 0;
            $user->is_admin = 1;
            $user->expire_in = date('Y-m-d H:i:s', \time() + $configs['sign_up_for_free_time'] * 86400);
            $user->reg_date = date('Y-m-d H:i:s');
            $user->money = 0;
            $user->im_type = 1;
            $user->im_value = '';
            $user->class = 0;
            $user->node_speedlimit = 0;
            $user->theme = $_ENV['theme'];

            $ga = new GA();
            $secret = $ga->createSecret();
            $user->ga_token = $secret;
            $user->ga_enable = 0;

            if ($user->save()) {
                echo '创建成功，请在主页登录' . PHP_EOL;
            } else {
                echo '创建失败，请检查数据库配置' . PHP_EOL;
            }
        } else {
            echo '已取消创建' . PHP_EOL;
        }
    }

    /**
     * 获取 USERID 的 Cookie
     */
    public function getCookie(): void
    {
        if (count($this->argv) === 4) {
            $user = ModelsUser::find($this->argv[3]);
            $expire_in = 86400 + \time();
            echo Hash::cookieHash($user->pass, $expire_in) . ' ' . $expire_in;
        }
    }

    /**
     * 为所有用户设置新的主题
     */
    public function setTheme(): void
    {
        fwrite(STDOUT, '请输入要设置的主题名称: ');
        $theme = trim(fgets(STDIN));
        $users = ModelsUser::all();
        foreach ($users as $user) {
            $user->theme = $theme;
            $user->save();
        }
    }
}
