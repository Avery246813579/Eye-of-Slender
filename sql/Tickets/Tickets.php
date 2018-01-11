<?php
/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 8/8/2015
 * Time: 4:41 PM
 */

class Tickets {
    public $ticket_id, $reporter_id, $assigned_id, $summary, $description, $reproduce, $additional_info, $category_id, $status, $reproducibility, $severity, $priority, $view, $system_spec, $tags;

    public function get($id){
        $mysqli = new mysqli("127.0.0.1", "felcraft_site", "3456sdfg78654g34x6r", 'felcraft_Tickets') or die('Kittens');

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

    public function create($reporter_id, $assigned_id, $summary, $description, $reproduce, $additional_info, $category_id, $status, $reproducibility, $severity, $priority, $view, $system_spec, $tags){
        $mysqli = new mysqli("127.0.0.1", "felcraft_site", "3456sdfg78654g34x6r", 'felcraft_Tickets') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Tickets (reporter_id, assigned_id, summary, description, reproduce, additional_info, category_id, status, reproducibility, severity, priority, view, system_spec, tags) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issi', $reporter_id, $assigned_id, $summary, $description, $reproduce, $additional_info, $category_id, $status, $reproducibility, $severity, $priority, $view, $system_spec, $tags);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "felcraft_site", "3456sdfg78654g34x6r", 'felcraft_Tickets') or die('Kittens');

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
        $mysqli = new mysqli("127.0.0.1", "felcraft_site", "3456sdfg78654g34x6r", 'felcraft_Tickets') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Forums WHERE forum_id = ?');
        $stmt->bind_param('i', $this->forum_id);
        $stmt->execute();

        $mysqli->close();
    }
}