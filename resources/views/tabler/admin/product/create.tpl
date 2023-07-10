{include file='admin/header.tpl'}

<div class="page-wrapper">
    <div class="container-xl">
        <div class="page-header d-print-none text-white">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <span class="home-title">创建商品</span>
                    </h2>
                    <div class="page-pretitle my-3">
                        <span class="home-subtitle">创建各类商品</span>
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a id="create-product" href="#" class="btn btn-primary">
                            <i class="icon ti ti-device-floppy"></i>
                            保存
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header card-header-light">
                            <h3 class="card-title">基础信息</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label required">名称</label>
                                <div class="col">
                                    <input id="name" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label required">价格</label>
                                <div class="col">
                                    <input id="price" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label required">库存（小于0时不限制）</label>
                                <div class="col">
                                    <input id="stock" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">销售状态</label>
                                <div class="col">
                                    <select id="status" class="col form-select">
                                        <option value="1">正常</option>
                                        <option value="0">下架</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">类型</label>
                                <div class="col">
                                    <select id="type" class="col form-select">
                                        <option value="tabp">时间流量包</option>
                                        <option value="time">时间包</option>
                                        <option value="bandwidth">流量包</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header card-header-light">
                            <h3 class="card-title">商品内容</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">商品时长 (天)</label>
                                <div class="col">
                                    <input id="time" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">等级</label>
                                <div class="col">
                                    <input id="class" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">等级时长 (天)</label>
                                <div class="col">
                                    <input id="class_time" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">可用流量 (GB)</label>
                                <div class="col">
                                    <input id="bandwidth" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label required">用户分组</label>
                                <div class="col">
                                    <input id="node_group" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label required">速率限制 (Mbps)</label>
                                <div class="col">
                                    <input id="speed_limit" type="text" class="form-control"
                                        value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label required">同时连接IP限制</label>
                                <div class="col">
                                    <input id="ip_limit" type="text" class="form-control"
                                        value="">
                                </div>
                            </div>
                            <div class="hr-text">
                                <span>购买限制</span>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">用户等级要求</label>
                                <div class="col">
                                    <input id="class_required" type="text" class="form-control"
                                        value="">
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label class="form-label col-3 col-form-label">用户所在的节点组</label>
                                <div class="col">
                                    <input id="node_group_required" type="text" class="form-control"
                                        value="">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="row">
                                    <span class="col">仅限新用户购买</span>
                                    <span class="col-auto">
                                        <label class="form-check form-check-single form-switch">
                                            <input id="new_user_required" class="form-check-input" type="checkbox">
                                        </label>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#create-product").click(function() {
        $.ajax({
            url: '/admin/product',
            type: 'POST',
            dataType: "json",
            data: {
                {foreach $update_field as $key}
                {$key}: $('#{$key}').val(),
                {/foreach}
                new_user_required: $("#new_user_required").is(":checked"),
            },
            success: function(data) {
                if (data.ret === 1) {
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

{include file='admin/footer.tpl'}
