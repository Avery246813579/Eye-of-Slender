<?php

/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 5/22/2015
 * Time: 8:17 PM
 */
class Forum
{
    public $forum_id, $category_id, $name, $description, $rank_id, $posts, $last_post;

    public function __construct($forum_id, $category_id, $name, $description, $rank_id)
    {
        $this->forum_id = $forum_id;
        $this->category_id = $category_id;
        $this->name = $name;
        $this->description = $description;
        $this->rank_id = $rank_id;
    }

    public function get($id){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Forums WHERE forum_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();

        $forum = null;
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($forum_id, $category_id, $name, $description, $rank_id);

            while ($stmt->fetch()) {
                $this->forum_id = $forum_id;
                $this->category_id = $category_id;
                $this->name = $name;
                $this->description = $description;
                $this->rank_id = $rank_id;
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

        $stmt = $mysqli->prepare('SELECT * FROM Posts WHERE forum_id = ?');
        $stmt->bind_param('i', $this->forum_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);
            $this->posts = array();

            include('Post.php');
            while ($stmt->fetch()) {
                array_push($this->posts, new Post($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted));
            }
        }

        $mysqli->close();
    }

    public function get_last_post()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('SELECT * FROM Posts WHERE forum_id = ? ORDER BY post_id DESC LIMIT 1');
        $stmt->bind_param('i', $this->forum_id);
        $stmt->execute();
        $stmt->store_result();

        $accounts = null;
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);

            include('Post.php');
            while ($stmt->fetch()) {
                $this->last_post = new Post($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);
            }
        }

        $mysqli->close();
    }

    public function create($category_id, $name, $description, $rank_id){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Forums (category_id, name, description, rank_id) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('issi', $category_id, $name, $description, $rank_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('UPDATE Forums SET category_id = ?, name = ?, description = ?, rank_id = ? WHERE forum_id = ?');
        $stmt->bind_param('issii', $this->category_id, $this->name, $this->description, $this->rank_id, $this->forum_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function delete()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Forums WHERE forum_id = ?');
        $stmt->bind_param('i', $this->forum_id);
        $stmt->execute();

        $mysqli->close();
    }
}