{include file='user/main.tpl'}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sukka/markdown.css">
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<style>
.tile-sub div {
    padding: 16px;
}
</style>

{*

如何添加教程：

1. 首先，整个 UI 分为几个部分

|| SSR || SS || V2Ray ||    <- 顶部的三个 Tab
========================
------------------------
| SSR Windows          |    <- 一个收起来的 Tile Collapse 组件
------------------------
------------------------
| SSR Android          |    <- 点击后展开的 Tile Collapse 组件
------------------------
|         教           |
|         程           |    <- 由 markdown 编写的教程放在 resources/views/material/user/markdown 目录下
|         内           |    <- 由浏览器将 markdown 渲染成 HTML
|         容           |
------------------------
------------------------
| SSTap                |    <- 又一个收起来的 Tile Collapse 组件
------------------------

Tab 负责控制 SSR / SS / V2Ray 的容器的切换，这些容器分别是 #tutorial_ssr #tutorial_ss #tutorial_v2ray

在容器中放置 Tile Collapse 组件，每个组件用于显示一种客户端的教程

以下是一个 Tile Collapse 组件的代码，缩进已经调整好，直接复制就可以用：

                                    <div class="tile tile-collapse">
                                        <div data-toggle="tile" data-target="#tutorial-heading-<!-- ID -->">
                                            <div class="tile-inner">
                                                <div class="text-overflow"><!-- 客户端名称 --></div>
                                            </div>
                                        </div>
                                        <div class="collapsible-region collapse" id="tutorial-heading-<!-- ID -->">
                                            <div class="tile-sub markdown-body">
                                                <div id="tutorial-<!--ID -->-content"></div>
                                                <script>
                                                    document.getElementById('tutorial-<!--ID -->-content').innerHTML = marked(`{include file='markdown/<!-- markdown 文件名 -->.md'}`);
                                                </script>
                                            </div>
                                        </div>
                                    </div>

！！注意！！
每个 Tile Collapse 组件的 ID 必须唯一！
*}

<main class="content">
    <div class="content-header ui-content-header">
        <div class="container">
            <h1 class="content-heading">使用教程</h1>
        </div>
    </div>

    <div class="container">
        <section class="content-inner margin-top-no">
            <div class="ui-card-wrap">

                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-main">

                            <div class="card-inner">
                                <nav class="tab-nav margin-top-no">
                                    <ul class="nav nav-list">
                                        <li class="active">
                                            <a class="" data-toggle="tab" href="#tutorial_ssr"><i class="icon icon-lg">airplanemode_active</i>&nbsp;SSR</a>
                                        </li>
                                        <li>
                                            <a class="" data-toggle="tab" href="#tutorial_ss"><i class="icon icon-lg">flight_takeoff</i>&nbsp;SS/SSD</a>
                                        </li>
                                        <li>
                                            <a class="" data-toggle="tab" href="#tutorial_v2ray"><i class="icon icon-lg">flight_land</i>&nbsp;V2RAY</a>
                                        </li>
                                    </ul>
                                </nav>

                                <div class="tab-pane fade active in page-course" id="tutorial_ssr">

                                    <div class="tile tile-collapse">
                                        <div data-toggle="tile" data-target="#tutorial-heading-ssrwin">
                                            <div class="tile-inner">
                                                <div class="text-overflow">ShadowsocksR / ShadowsocksRR Windows</div>
                                            </div>
                                        </div>
                                        <div class="collapsible-region collapse" id="tutorial-heading-ssrwin">
                                            <div class="tile-sub markdown-body">
                                                <div id="tutorial-ssrwin-content"></div>
                                                <script>
                                                    document.getElementById('tutorial-ssrwin-content').innerHTML = marked(`{include file='markdown/ssr-win.md'}`);
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tile tile-collapse">
                                        <div data-toggle="tile" data-target="#tutorial-heading-ssrmac">
                                            <div class="tile-inner">
                                                <div class="text-overflow">ShadowsocksX-NG-R8 macOS</div>
                                            </div>
                                        </div>
                                        <div class="collapsible-region collapse" id="tutorial-heading-ssrmac">
                                            <div class="tile-sub markdown-body">
                                                <div id="tutorial-ssrmac-content"></div>
                                                <script>
                                                    document.getElementById('tutorial-ssrmac-content').innerHTML = marked(`{include file='markdown/ssr-mac.md'}`);
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tile tile-collapse">
                                        <div data-toggle="tile" data-target="#tutorial-heading-ssr-android">
                                            <div class="tile-inner">
                                                <div class="text-overflow">SSR / SSRR Android</div>
                                            </div>
                                        </div>
                                        <div class="collapsible-region collapse" id="tutorial-ssr-android">
                                            <div class="tile-sub markdown-body">
                                                <div id="tutorial-ssr-android-content"></div>
                                                <script>
                                                    document.getElementById('tutorial-ssr-android-content').innerHTML = marked(`{include file='markdown/ssr-android.md'}`);
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tile tile-collapse">
                                        <div data-toggle="tile" data-target="#tutorial-heading-ssr-shadowrocket">
                                            <div class="tile-inner">
                                                <div class="text-overflow">Shadowrocket iOS</div>
                                            </div>
                                        </div>
                                        <div class="collapsible-region collapse" id="tutorial-ssr-shadowrocket">
                                            <div class="tile-sub markdown-body">
                                                <div id="tutorial-ssr-shadowrocket-content"></div>
                                                <script>
                                                    document.getElementById('tutorial-ssr-shadowrocket-content').innerHTML = marked(`{include file='markdown/shadowrocket.md'}`);
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade page-course" id="tutorial_ss">

                                    <div class="tile tile-collapse">
                                        <div data-toggle="tile" data-target="#tutorial-heading-ssqt5">
                                            <div class="tile-inner">
                                                <div class="text-overflow">Shadowsocks Qt5 Linux</div>
                                            </div>
                                        </div>
                                        <div class="collapsible-region collapse" id="tutorial-heading-ssqt5">
                                            <div class="tile-sub markdown-body">
                                                <div id="tutorial-ssqt5-content"></div>
                                                <script>
                                                    document.getElementById('tutorial-ssqt5-content').innerHTML = marked(`{include file='markdown/ss-qt5.md'}`);
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade page-course" id="tutorial_v2ray">
                                </div>
                            </div>

                        </div>
                    </div>


                    {include file='dialog.tpl'}

                </div>


            </div>
        </section>
    </div>
</main>


{include file='user/footer.tpl'}



