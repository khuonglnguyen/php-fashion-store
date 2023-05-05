<?php
// $chat = new chat();
// $data = $chat->getData();
?>
<div class="row">
    <div id="box" class="chatbox chatbox22 chatbox--tray">
        <div class="chatbox__title">
            <h5><a href="javascript:void()" onclick="box(this)">Chat với chúng tôi</a></h5>
            <button class="chatbox__title__close">
                <span>
                    <svg viewBox="0 0 12 12" width="12px" height="12px">
                        <line stroke="#FFFFFF" x1="11.75" y1="0.25" x2="0.25" y2="11.75"></line>
                        <line stroke="#FFFFFF" x1="11.75" y1="11.75" x2="0.25" y2="0.25"></line>
                    </svg>
                </span>
            </button>
        </div>
        <div class="chatbox__body" id="chat-body">
            <div class="chatbox__body__message chatbox__body__message--left">
                <img src="<?= URL_ROOT ?>/public/images/user.png" alt="Picture">
                <div class="clearfix"></div>
                <div class="ul_section_full">
                    <ul class="ul_msg">
                        <li><strong>Admin</strong></li>
                        <?php
                        if (!isset($_SESSION['user_id'])) { ?>
                            <li>Vui lòng <a href="<?= URL_ROOT ?>/user/login">ĐĂNG NHẬP</a> để chat!</li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <li>Chào bạn!</li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
</div>
<div class="panel-footer">
    <div class="input-group">
        <input id="btn-input" type="text" required class="form-control input-sm chat_set_height" placeholder="Soạn tin nhắn..." tabindex="0" dir="ltr" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" contenteditable="true" />
        <span class="input-group-btn">
            <button class="btn bt_bg btn-sm" id="btn-chat" onclick="send()">
                Gửi
            </button>
        </span>
    </div>
</div>
<?php }
?>

</div>
</div>
<script>
    var userId = "<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "" ?>";
    var myDiv = document.getElementById('chat-body');
    var refreshIntervalId = 0;
    if (userId) {
        loadData();
        refreshIntervalId = window.setInterval(function() {
            loadData();
        }, 5000);

        myDiv.scrollTop = 10000000;
    }


    function box() {
        document.getElementById('box').classList.toggle("chatbox--tray");
    }

    var element = document.getElementById("btn-input");
    element.addEventListener("keypress", function(event) {
        if (event.key === "Enter" && element.value != "") {
            send();
        }
    });

    function send() {
        var queries = document.getElementById('btn-input').value;
        document.getElementById('chat-body').innerHTML += '<div class="chatbox__body__message chatbox__body__message--right">' +
            '<img src="http://localhost/luanvan/public/images/user.png" alt="Picture">' +
            '<div class="clearfix"></div>' +
            ' <div class="ul_section_full">' +
            ' <ul class="ul_msg">' +
            '<li><strong>Bạn</strong></li>' +
            '<li>' + queries + '</li>' +
            '</ul>' +
            '<div class="clearfix"></div>' +
            '</div>' +
            '</div>';
        document.getElementById('btn-input').value = "";
        myDiv.scrollTop = 10000000;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "http://localhost/luanvan/chat/send/" + queries, true);
        xhr.onload = function() {
            if (xhr.readyState === 4) {
                var status = xhr.status;
                if (status === 200) {
                    var res = JSON.parse(this.responseText);
                    console.log(res);
                    document.getElementById('chat-body').innerHTML += '<div class="chatbox__body__message chatbox__body__message--left">' +
                        '<img src="http://localhost/luanvan/public/images/admin.png" alt="Picture">' +
                        '<div class="clearfix"></div>' +
                        ' <div class="ul_section_full">' +
                        ' <ul class="ul_msg">' +
                        '<li><strong>Admin</strong></li>' +
                        '<li>' + res.replies + '</li>' +
                        '</ul>' +
                        '<div class="clearfix"></div>' +
                        '</div>' +
                        '</div>';

                } else {
                    document.getElementById('chat-body').innerHTML += '<div class="chatbox__body__message chatbox__body__message--left">' +
                        '<img src="http://localhost/luanvan/public/images/cry-sad.gif" alt="Picture">' +
                        '<div class="clearfix"></div>' +
                        ' <div class="ul_section_full">' +
                        ' <ul class="ul_msg">' +
                        '<li><strong>Server</strong></li>' +
                        '<li>Lỗi không thể gửi tin nhắn!</li>' +
                        '</ul>' +
                        '<div class="clearfix"></div>' +
                        '</div>' +
                        '</div>';
                    clearInterval(refreshIntervalId);
                    sleep(2000);
                    refreshIntervalId = window.setInterval(function() {
                        loadData();
                    }, 5000);
                }
                myDiv.scrollTop = 10000000;
            }
        };
        xhr.onerror = function(e) {
            console.error(xhr.statusText);
        };
        xhr.send(null);
    }

    function loadData() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "http://localhost/luanvan/chat/getData", true);
        xhr.onload = function() {
            if (xhr.readyState === 4) {
                document.getElementById('chat-body').innerHTML = "";

                var status = xhr.status;
                if (status === 200) {
                    var res = JSON.parse(this.responseText);
                    if (res.length > 0) {
                        for (let index = 0; index < res.length; index++) {
                            if (res[index].fromUserId == userId) {
                                document.getElementById('chat-body').innerHTML += '<div class="chatbox__body__message chatbox__body__message--right">' +
                                    '<img src="http://localhost/luanvan/public/images/user.png" alt="Picture">' +
                                    '<div class="clearfix"></div>' +
                                    ' <div class="ul_section_full">' +
                                    ' <ul class="ul_msg">' +
                                    '<li><strong>Bạn</strong></li>' +
                                    '<li>' + res[index].content + '</li>' +
                                    '</ul>' +
                                    '<div class="clearfix"></div>' +
                                    '</div>' +
                                    '</div>';
                            } else {
                                document.getElementById('chat-body').innerHTML += '<div class="chatbox__body__message chatbox__body__message--left">' +
                                    '<img src="http://localhost/luanvan/public/images/admin.png" alt="Picture">' +
                                    '<div class="clearfix"></div>' +
                                    ' <div class="ul_section_full">' +
                                    ' <ul class="ul_msg">' +
                                    '<li><strong>Admin</strong></li>' +
                                    '<li>' + res[index].content + '</li>' +
                                    '</ul>' +
                                    '<div class="clearfix"></div>' +
                                    '</div>' +
                                    '</div>';
                            }
                        }
                    } else {
                        document.getElementById('chat-body').innerHTML += '<div class="chatbox__body__message chatbox__body__message--left">' +
                            '<img src="http://localhost/luanvan/public/images/admin.png" alt="Picture">' +
                            '<div class="clearfix"></div>' +
                            ' <div class="ul_section_full">' +
                            ' <ul class="ul_msg">' +
                            '<li><strong>Admin</strong></li>' +
                            '<li>Chào bạn, bạn có cần tư vấn gì không ạ?</li>' +
                            '</ul>' +
                            '<div class="clearfix"></div>' +
                            '</div>' +
                            '</div>';
                    }
                }
                myDiv.scrollTop = 10000000;
            }
        };
        xhr.onerror = function(e) {
            console.error(xhr.statusText);
        };
        xhr.send(null);
    }

    function sleep(ms) {
        return new Promise((resolve) => {
            setTimeout(resolve, ms);
        });
    }
</script>