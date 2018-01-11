<?php
/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 5/24/2015
 * Time: 1:33 PM
 */

class Notifications {
    public $notification_id, $account_id, $content, $link, $icon, $notified, $type;

    // 0. Friends 1. Message 2. Instants 3. Regular
    public function __construct($notification_id, $account_id, $content, $link, $icon, $notified, $type){
        $this->notification_id = $notification_id;
        $this->account_id = $account_id;
        $this->content = $content;
        $this->link = $link;
        $this->icon = $icon;
        $this->notified = $notified;
        $this->type = $type;
    }

    public function create($account_id, $content, $link, $icon, $notified, $type){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Notifications (account_id, content, link, icon, notified, type) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issssi', $account_id, $content, $link, $icon, $notified, $type);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('UPDATE Notifications SET account_id = ?, content = ?, link = ?, icon = ?, notified = ?, type = ? WHERE notification_id = ?');
        $stmt->bind_param('issssii', $this->account_id, $this->content, $this->link, $this->icon, $this->notified, $this->type, $this->notification_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function delete()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Notifications WHERE notification_id = ?');
        $stmt->bind_param('i', $this->notification_id);
        $stmt->execute();

        $mysqli->close();
    }
}