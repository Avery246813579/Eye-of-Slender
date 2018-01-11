<?php
/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 7/25/2015
 * Time: 3:20 PM
 */

class Comment {
    public $comment_id, $post_id, $account_id, $subject, $likes, $dislikes;

    public function __construct($comment_id, $post_id, $account_id, $subject, $likes, $dislikes){
        $this->$comment_id = $comment_id;
        $this->post_id = $post_id;
        $this->account_id = $account_id;
        $this->subject = $subject;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
    }

    public function get($id){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Comments WHERE comment_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($comment_id, $post_id, $account_id, $subject, $likes, $dislikes);

            while ($stmt->fetch()) {
                $this->$comment_id = $comment_id;
                $this->post_id = $post_id;
                $this->account_id = $account_id;
                $this->subject = $subject;
                $this->likes = $likes;
                $this->dislikes = $dislikes;
            }
        }

        $mysqli->close();

    }

    public function create($post_id, $account_id, $subject, $likes, $dislikes){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Comments (post_id, account_id, subject, likes, dislikes) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('iisii', $post_id, $account_id, $subject, $likes, $dislikes);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('UPDATE Comments SET post_id = ?, account_id = ?, subject = ?, likes = ?, dislikes = ? WHERE comment_id = ?');
        $stmt->bind_param('iisiii', $this->post_id, $this->account_id, $this->subject, $this->likes, $this->dislikes, $this->comment_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function delete()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Comments WHERE comment_id = ?');
        $stmt->bind_param('i', $this->comment_id);
        $stmt->execute();

        $mysqli->close();
    }

}