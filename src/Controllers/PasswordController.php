<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\PasswordReset;
use App\Models\Setting;
use App\Models\User;
use App\Services\Captcha;
use App\Services\Password;
use App\Utils\Hash;
use App\Utils\ResponseHelper;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

/*
 * Class Password
 *
 * @package App\Controllers
 * 密码重置
 */
final class PasswordController extends BaseController
{
    /**
     * @param array     $args
     */
    public function reset(ServerRequest $request, Response $response, array $args)
    {
        $captcha = [];

        if (Setting::obtain('enable_reset_password_captcha') === true) {
            $captcha = Captcha::generate();
        }

        return $response->write(
            $this->view()
                ->assign('captcha', $captcha)
                ->fetch('password/reset.tpl')
        );
    }

    /**
     * @param array     $args
     */
    public function handleReset(ServerRequest $request, Response $response, array $args)
    {
        if (Setting::obtain('enable_reset_password_captcha') === true) {
            $ret = Captcha::verify($request->getParams());
            if (! $ret) {
                return ResponseHelper::error($response, '系统无法接受您的验证结果，请刷新页面后重试');
            }
        }

        $email = strtolower($request->getParam('email'));
        $user = User::where('email', $email)->first();

        if ($user === null) {
            $msg = '如果你的账户存在于我们的数据库中，那么重置密码的链接将会发送到你账户所对应的邮箱。';
        }

        if (Password::sendResetEmail($email)) {
            $msg = '如果你的账户存在于我们的数据库中，那么重置密码的链接将会发送到你账户所对应的邮箱。';
        } else {
            $msg = '邮件发送失败，请联系网站管理员。';
        }

        return ResponseHelper::successfully($response, $msg);
    }

    /**
     * @param array     $args
     */
    public function token(ServerRequest $request, Response $response, array $args)
    {
        $token = PasswordReset::where('token', $args['token'])->where('expire_time', '>', \time())->orderBy('id', 'desc')->first();
        if ($token === null) {
            return $response->withStatus(302)->withHeader('Location', '/password/reset');
        }

        return $response->write(
            $this->view()->fetch('password/token.tpl')
        );
    }

    /**
     * @param array     $args
     */
    public function handleToken(ServerRequest $request, Response $response, array $args)
    {
        $tokenStr = $args['token'];
        $password = $request->getParam('password');
        $repasswd = $request->getParam('repasswd');

        if ($password !== $repasswd) {
            return ResponseHelper::error($response, '两次输入不符合');
        }

        if (strlen($password) < 8) {
            return ResponseHelper::error($response, '密码太短啦');
        }

        /** @var PasswordReset $token */
        $token = PasswordReset::where('token', $tokenStr)->where('expire_time', '>', \time())->orderBy('id', 'desc')->first();
        if ($token === null) {
            return ResponseHelper::error($response, '链接已经失效，请重新获取');
        }

        $user = $token->getUser();
        if ($user === null) {
            return ResponseHelper::error($response, '链接已经失效，请重新获取');
        }

        // reset password
        $hashPassword = Hash::passwordHash($password);
        $user->pass = $hashPassword;
        $user->ga_enable = 0;

        if (! $user->save()) {
            return ResponseHelper::error($response, '重置失败，请重试');
        }

        if ($_ENV['enable_forced_replacement'] === true) {
            $user->cleanLink();
        }

        // 禁止链接多次使用
        $token->expire_time = \time();
        $token->save();

        return ResponseHelper::successfully($response, '重置成功');
    }
}
