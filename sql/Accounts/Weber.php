<?php

/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 5/22/2015
 * Time: 8:17 PM
 */
class Weber
{
    public $weber_id, $account_id, $email, $joined, $last_log, $reputation, $last_ip, $online;
    public $requests = array(), $notifications = array(), $posts, $comments, $last_post;

    public function __construct($identifier)
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        if (is_int($identifier)) {
            $stmt = $mysqli->prepare('SELECT * FROM Webers WHERE account_id = ?');
            $stmt->bind_param('i', $identifier);
            $stmt->execute();
            $stmt->store_result();

            $accounts = null;
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($weber_id, $account_id, $email, $joined, $last_log, $reputation, $last_ip, $online);

                while ($stmt->fetch()) {
                    $this->weber_id = $weber_id;
                    $this->account_id = $account_id;
                    $this->email = $email;
                    $this->joined = $joined;
                    $this->last_log = $last_log;
                    $this->reputation = $reputation;
                    $this->last_ip = $last_ip;
                    $this->online = $online;
                }
            }
        }

        $mysqli->close();
    }

    public function load_last_post()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Posts WHERE account_id = ? ORDER BY post_id DESC LIMIT 1');
        $stmt->bind_param('i', $this->account_id);
        $stmt->execute();
        $stmt->store_result();


        if ($stmt->num_rows > 0) {
            $stmt->bind_result($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);

            while ($stmt->fetch()) {
                $this->last_post = new Post($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);
            }
        }

        $mysqli->close();
    }

    public function load_posts()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Posts WHERE account_id = ?');
        $stmt->bind_param('i', $this->account_id);
        $stmt->execute();
        $stmt->store_result();


        if ($stmt->num_rows > 0) {
            $posts = 0;

            while ($stmt->fetch()) {
                $posts++;
            }

            $this->posts = $posts;
        }

        $mysqli->close();
    }

    public function load_comments()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Comments WHERE account_id = ?');
        $stmt->bind_param('i', $this->account_id);
        $stmt->execute();
        $stmt->store_result();


        if ($stmt->num_rows > 0) {
            $comments = 0;

            while ($stmt->fetch()) {
                $comments++;
            }

            $this->comments = $comments;
        }

        $mysqli->close();
    }

    public function load_requests(){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Requests WHERE requested_id = ?');
        $stmt->bind_param('i', $this->account_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($request_id, $requester_id, $requested_id);
            $this->requests = array();

            include('../Social/Requests.php');
            while ($stmt->fetch()) {
                array_push($this->requests, new Requests($request_id, $requester_id, $requested_id));
            }

            $this->requests = array_reverse($this->requests);
        }

        $mysqli->close();
    }

    public function load_notifications(){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

//        $stmt = $mysqli->prepare('SELECT * FROM Notifications WHERE account_id = ?');
//        $stmt->bind_param('i', $this->account_id);
//        $stmt->execute();
//        $stmt->store_result();
//
//        if ($stmt->num_rows > 0) {
//            $stmt->bind_result($notification_id, $account_id, $content, $link, $icon, $notified, $type);
//            $this->notifications = array();
//
//            include('../Social/Notifications.php');
//            while ($stmt->fetch()) {
//                array_push($this->notifications, new Notifications($notification_id, $account_id, $content, $link, $icon, $notified, $type));
//            }
//
//            $this->notifications = array_reverse($this->notifications);
//        }
//
//        $mysqli->close();
        $this->notifications = array();
    }

    public function create($account_id, $email, $joined, $last_log, $reputation, $last_ip, $online){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Webers (account_id, email, joined, last_log, reputation, last_ip, online) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isssisi', $account_id, $email, $joined, $last_log, $reputation, $last_ip, $online);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('UPDATE Webers SET account_id = ?, email = ?, joined = ?, last_log = ?, reputation = ?, last_ip = ?, online = ? WHERE weber_id = ?');
        $stmt->bind_param('isssisii', $this->account_id, $this->email, $this->joined, $this->last_log, $this->reputation, $this->last_ip, $this->online, $this->weber_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function delete()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Webers WHERE weber_id = ?');
        $stmt->bind_param('i', $this->weber_id);
        $stmt->execute();

        $mysqli->close();
    }
}