<?php

class chat extends ControllerBase
{
    public function send($content)
    {
        header('Content-Type: application/json; charset=utf-8');
        $message = $this->model("messageModel");
        if ($message->insert($_SESSION['user_id'], 59, $content)) {
            $bot = $this->model("botModel");
            $result = $bot->getReplies($content);
            http_response_code(200);
            if ($result) {
                // Insert DB
                $message->insert(59, $_SESSION['user_id'], $result['replies']);
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            } 
            // else {
            //     // $message->insert(59, $_SESSION['user_id'], 'Cảm ơn bạn đã nhắn tin, chúng tôi sẽ phản hồi sớm nhất có thể!');
            //     echo json_encode([
            //         "replies" => "Cảm ơn bạn đã nhắn tin, chúng tôi sẽ phản hồi sớm nhất có thể!"
            //     ], JSON_UNESCAPED_UNICODE);
            // }
        } else {
            http_response_code(500);
        }
    }

    public function sendAdmin($content)
    {
        header('Content-Type: application/json; charset=utf-8');
        $message = $this->model("messageModel");
        $result = $message->insert($_SESSION['user_id'], 59, $content);
        if ($result) {
            // Insert DB
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }

    public function getData()
    {
        header('Content-Type: application/json; charset=utf-8');
        $message = $this->model("messageModel");
        $result = ($message->getData($_SESSION['user_id'], 59))->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function chatList()
    {
        $message = $this->model("messageModel");
        $result = ($message->getUserChating($_SESSION['user_id'], 59))->fetch_all(MYSQLI_ASSOC);
        $this->view("admin/chatList", [
            "headTitle" => "Chat với khách hàng",
            "userList" => $result
        ]);
    }

    public function chating($userId)
    {
        header('Content-Type: application/json; charset=utf-8');
        $message = $this->model("messageModel");
        $result = ($message->getDataChating($_SESSION['user_id'], $userId))->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
