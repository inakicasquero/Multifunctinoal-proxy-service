{include file='admin/tabler_header.tpl'}

<div class="page-wrapper">
    <div class="container-xl">
        <div class="page-header d-print-none text-white">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <span class="home-title" style="line-height: unset;">用户 #{$edit_user->id}</span>
                    </h2>
                    <div class="page-pretitle">
                        <span class="home-subtitle">用户编辑</span>
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a id="save_changes" href="#" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="icon ti ti-device-floppy"></i>
                            保存
                        </a>
                        <a id="save_changes" href="#" class="btn btn-primary d-sm-none btn-icon">
                            <i class="icon ti ti-device-floppy"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header card-header-light">
                            <h3 class="card-title">基础信息</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">注册邮箱</label>
                                <div class="col">
                                    <input id="email" type="email" class="form-control" value="{$edit_user->email}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">用户名</label>
                                <div class="col">
                                    <input id="user_name" type="text" class="form-control"
                                        value="{$edit_user->user_name}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">备注</label>
                                <div class="col">
                                    <input id="remark" type="text" class="form-control" value="{$edit_user->remark}"
                                        placeholder="仅管理员可见">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">重置密码</label>
                                <div class="col">
                                    <input id="reset_user_passwd" type="text" class="form-control"
                                        placeholder="若需为此用户重置密码, 填写此栏">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">账户余额</label>
                                <div class="col">
                                    <input id="money" type="number" step="0.1" class="form-control"
                                        value="{$edit_user->money}">
                                </div>
                            </div>
                            <div class="hr-text">
                                <span>时间设置</span>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">等级过期时间</label>
                                <div class="col">
                                    <input id="class_expire" type="text" class="form-control"
                                        value="{$edit_user->class_expire}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">账户过期时间</label>
                                <div class="col">
                                    <input id="expire_in" type="text" class="form-control"
                                        value="{$edit_user->expire_in}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">免费用户流量重置日</label>
                                <div class="col">
                                    <input id="auto_reset_day" type="text" class="form-control"
                                        value="{$edit_user->auto_reset_day}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">
                                重置的免费流量(GB)
                                </label>
                                <div class="col">
                                    <input id="auto_reset_bandwidth" type="text" class="form-control"
                                        value="{$edit_user->auto_reset_bandwidth}">
                                </div>
                            </div>
                            <div class="hr-text">
                                <span>高级选项</span>
                            </div>
                            <div class="mb-3">
                                <div class="divide-y">
                                    <div>
                                        <label class="row">
                                            <span class="col">管理员</span>
                                            <span class="col-auto">
                                                <label class="form-check form-check-single form-switch">
                                                    <input id="is_admin" class="form-check-input" type="checkbox"
                                                        {if $edit_user->is_admin == 1}checked="" {/if}>
                                                </label>
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="row">
                                            <span class="col">封禁用户</span>
                                            <span class="col-auto">
                                                <label class="form-check form-check-single form-switch">
                                                    <input id="is_banned" class="form-check-input" type="checkbox"
                                                        {if $edit_user->is_banned == 1} checked=""{/if}>
                                                </label>
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="row">
                                            <span class="col">手动封禁理由</span>
                                            <span class="col-auto">
                                                <input id="banned_reason" type="text" class="form-control"
                                                    value="{$edit_user->banned_reason}">
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="row">
                                            <span class="col">两步认证</span>
                                            <span class="col-auto">
                                                <label class="form-check form-check-single form-switch">
                                                    <input id="ga_enable" class="form-check-input" type="checkbox"
                                                        {if $edit_user->ga_enable == 1}checked="" {/if}>
                                                </label>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header card-header-light">
                            <h3 class="card-title">其他信息</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">流量限制 (GB)</label>
                                <div class="col">
                                    <input id="transfer_enable" type="text" class="form-control"
                                        value="{$edit_user->enableTrafficInGB()}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">已用流量 (GB)</label>
                                <div class="col">
                                    <input id="usedTraffic" type="text" class="form-control"
                                        value="{$edit_user->usedTraffic()}" disabled />
                                </div>
                            </div>
                            <div class="hr-text">
                                <span>邀请注册</span>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">可用邀请数量</label>
                                <div class="col">
                                    <input id="invite_num" type="text" class="form-control"
                                        value="{$edit_user->invite_num}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-4 col-form-label">邀请人</label>
                                <div class="col">
                                    <input id="ref_by" type="text" class="form-control" value="{$edit_user->ref_by}">
                                </div>
                            </div>
                            <div class="hr-text">
                                <span>划分与使用限制</span>
                            </div>
                            <div class="form-group mb-3 col-12">
                                    <label class="form-label col-12 col-form-label">节点群组</label>
                                    <div class="col">
                                        <input id="node_group" type="text" class="form-control"
                                            value="{$edit_user->node_group}">
                                    </div>
                                </div>
                                <div class="form-group mb-3 col-12">
                                    <label class="form-label col-12 col-form-label">账户等级</label>
                                    <div class="col">
                                        <input id="class" type="text" class="form-control" value="{$edit_user->class}">
                                    </div>
                                </div>
                                <div class="form-group mb-3 col-12">
                                    <label class="form-label col-12 col-form-label">速度限制 (Mbps)</label>
                                    <div class="col">
                                        <input id="node_speedlimit" type="text" class="form-control"
                                            value="{$edit_user->node_speedlimit}">
                                    </div>
                                </div>
                                <div class="form-group mb-3 col-12">
                                    <label class="form-label col-12 col-form-label">链接设备限制</label>
                                    <div class="col">
                                        <input id="node_connector" type="text" class="form-control"
                                            value="{$edit_user->node_connector}">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header card-header-light">
                            <h3 class="card-title">连接设置</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">端口</label>
                                <div class="col">
                                    <input id="port" type="text" class="form-control" value="{$edit_user->port}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">密码</label>
                                <div class="col">
                                    <input id="passwd" type="text" class="form-control" value="{$edit_user->passwd}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">加密</label>
                                <div class="col">
                                    <input id="method" type="text" class="form-control" value="{$edit_user->method}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">协议</label>
                                <div class="col">
                                    <input id="protocol" type="text" class="form-control"
                                        value="{$edit_user->protocol}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">协议参数</label>
                                <div class="col">
                                    <input id="protocol_param" type="text" class="form-control"
                                        value="{$edit_user->protocol_param}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">混淆方式</label>
                                <div class="col">
                                    <input id="obfs" type="text" class="form-control" value="{$edit_user->obfs}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">混淆参数</label>
                                <div class="col">
                                    <input id="obfs_param" type="text" class="form-control"
                                        value="{$edit_user->obfs_param}">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">单端口多用户承载端口</label>
                                <div class="col">
                                    <select id="is_multi_user" class="col form-select">
                                        <option value="0">非单端口多用户承载端口</option>
                                        <option value="1">混淆式单端口多用户承载端口</option>
                                        <option value="2">协议式单端口多用户承载端口</option>
                                    </select>
                                </div>
                            </div>
                            <div class="hr-text">
                                <span>访问限制</span>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">IP / CIDR</label>
                                <div class="col">
                                    <textarea id="forbidden_ip" class="col form-control"
                                        rows="2">{$edit_user->forbidden_ip}</textarea>
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">PORT</label>
                                <div class="col">
                                    <textarea id="forbidden_port" class="col form-control"
                                        rows="2">{$edit_user->forbidden_port}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#is_multi_user").prop('value', '{$edit_user->is_multi_user}');

    $("#save_changes").click(function() {
        $.ajax({
            url: '/admin/user/{$edit_user->id}',
            type: 'PUT',
            dataType: "json",
            data: {
                {foreach $update_field as $key}
                    {$key}: $('#{$key}').val(),
                {/foreach}
                is_admin: $("#is_admin").is(":checked"),
                is_banned: $("#enable").is(":checked"),
                ga_enable: $("#ga_enable").is(":checked"),
            },
            success: function(data) {
                if (data.ret == 1) {
                    $('#success-message').text(data.msg);
                    $('#success-dialog').modal('show');
                    window.setTimeout("location.href=top.document.referrer", {$config['jump_delay']});
                } else {
                    $('#fail-message').text(data.msg);
                    $('#fail-dialog').modal('show');
                }
            }
        })
    });
</script>

{include file='admin/tabler_footer.tpl'}