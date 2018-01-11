<?php
/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 5/24/2015
 * Time: 1:37 PM
 */

class Requests {
    public $request_id, $requester_id, $requested_id;

    public function __construct($request_id, $requester_id, $requested_id){
        $this->request_id = $request_id;
        $this->requester_id = $requester_id;
        $this->requested_id = $requested_id;
    }

    public function create($requester_id, $requested_id){
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Requests (requester_id, requested_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $requester_id, $requested_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('UPDATE Requests SET requester_id = ?, requested_id = ? WHERE request_id = ?');
        $stmt->bind_param('iii', $this->requester_id, $this->requested_id, $this->request_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function delete()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Requests WHERE request_id = ?');
        $stmt->bind_param('i', $this->request_id);
        $stmt->execute();

        $mysqli->close();
    }
}