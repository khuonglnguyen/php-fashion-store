<?php
// $chat = new chat();
// $data = $chat->getData();
?>
<div class="row">
    <div id="box" class="chatbox chatbox22 chatbox--tray">
        <div class="chatbox__title">
            <h5><a href="javascript:void()" onclick="boxchat(this)" id="title-chat">CHAT</a></h5>
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

        </div>
        <div class="clearfix"></div>
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
    </div>
    <script>
        document.getElementById('box').classList.toggle("chatbox--closed");

        var userId = "<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "" ?>";
        var myDiv = document.getElementById('chat-body');
        var userName = "";
        var id = 0;

        function box(e) {
            document.getElementById('box').classList.toggle("chatbox--closed");
            id = e.getAttribute('data-id');
            userName = e.getAttribute('data-userName');
            document.getElementById('title-chat').innerHTML = userName;
            document.getElementById('box').classList.toggle("chatbox--tray");

            loadData(e.getAttribute('data-id'));
            window.setInterval(function() {
                loadData(e.getAttribute('data-id'));
            }, 5000);
        }

        function boxchat(e) {
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
                '<img src="' + window.location.origin + '/luanvan/public/images/admin.png" alt="Picture">' +
                '<div class="clearfix"></div>' +
                ' <div class="ul_section_full">' +
                ' <ul class="ul_msg">' +
                '<li><strong>Bạn</strong></li>' +
                '<li>' + queries + '</li>' +
                '</ul>' +
                '<div class="clearfix"></div>' +
                '</div>' +
                '</div>';

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "http://localhost/luanvan/chat/sendAdmin/" + queries, true);
            xhr.onload = function() {
                if (xhr.readyState === 4) {
                    var status = xhr.status;
                    if (status === 500) {
                        document.getElementById('chat-body').innerHTML += '<div class="chatbox__body__message chatbox__body__message--left">' +
                            '<img src="' + window.location.origin + '/luanvan/public/images/cry-sad.gif" alt="Picture">' +
                            '<div class="clearfix"></div>' +
                            ' <div class="ul_section_full">' +
                            ' <ul class="ul_msg">' +
                            '<li><strong>Server</strong></li>' +
                            '<li>Lỗi không thể gửi tin nhắn!</li>' +
                            '</ul>' +
                            '<div class="clearfix"></div>' +
                            '</div>' +
                            '</div>';
                    } else {
                        document.getElementById('btn-input').value = "";
                        document.getElementById('btn-chat').innerHTML = "Gửi";
                    }
                    myDiv.scrollTop = 10000000;
                }
            };
            xhr.onerror = function(e) {
                console.error(xhr.statusText);
            };
            xhr.send(null);
        }

        function loadData(e) {
            document.getElementById('chat-body').innerHTML = "";
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "http://localhost/luanvan/chat/chating/" + e, true);
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
                                        '<img src="' + window.location.origin + '/luanvan/public/images/admin.png" alt="Picture">' +
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
                                        '<img src="' + window.location.origin + '/luanvan/public/images/user.png" alt="Picture">' +
                                        '<div class="clearfix"></div>' +
                                        ' <div class="ul_section_full">' +
                                        ' <ul class="ul_msg">' +
                                        '<li><strong>' + userName + '</strong></li>' +
                                        '<li>' + res[index].content + '</li>' +
                                        '</ul>' +
                                        '<div class="clearfix"></div>' +
                                        '</div>' +
                                        '</div>';
                                }
                            }
                        }
                    }
                    myDiv.scrollTop = 10000000;
                };
            }
            xhr.onerror = function(e) {
                console.error(xhr.statusText);
            };
            xhr.send(null);
        }
    </script>