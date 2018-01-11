<?php

/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 7/24/2015
 * Time: 9:10 PM
 */
class Post
{
    public $post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted, $comments;

    public function __construct($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted)
    {
        $this->post_id = $post_id;
        $this->forum_id = $forum_id;
        $this->account_id = $account_id;
        $this->subject = $subject;
        $this->body = $body;
        $this->views = $views;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
        $this->posted = $posted;
    }

    public function get_post($id)
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Posts WHERE post_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();

        $accounts = null;
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);

            include('Post.php');
            while ($stmt->fetch()) {
                $this->post_id = $post_id;
                $this->forum_id = $forum_id;
                $this->account_id = $account_id;
                $this->subject = $subject;
                $this->body = $body;
                $this->views = $views;
                $this->likes = $likes;
                $this->dislikes = $dislikes;
                $this->posted = $posted;
            }
        }

        $mysqli->close();
    }

    public function load_comments(){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Comments WHERE post_id = ?');
        $stmt->bind_param('i', $this->post_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($comment_id, $post_id, $account_id, $subject, $likes, $dislikes);
            $this->comments = array();

            include('Comment.php');
            while ($stmt->fetch()) {
                array_push($this->comments, new Comment($comment_id, $post_id, $account_id, $subject, $likes, $dislikes));
            }
        }

        $mysqli->close();
    }

    public function create($forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Posts (forum_id, account_id, subject, body, views, likes, dislikes, posted) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('iissiiis', $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('UPDATE Posts SET forum_id = ?, account_id = ?, subject = ?, body = ?, views = ?, likes = ?, dislikes = ?, posted = ? WHERE post_id = ?');
        $stmt->bind_param('iissiiisi', $this->forum_id, $this->account_id, $this->subject, $this->body, $this->views, $this->likes, $this->dislikes, $this->posted, $this->post_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function delete()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Posts WHERE post_id = ?');
        $stmt->bind_param('i', $this->post_id);
        $stmt->execute();

        $mysqli->close();
    }
}