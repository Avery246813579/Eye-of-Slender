<?php

/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 5/22/2015
 * Time: 8:17 PM
 */
class Accounts
{
    public $account_id, $username, $uuid, $rank, $coins, $credits, $xp, $achievements, $clan;

    public function __construct($identifier)
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        if (is_numeric($identifier)) {
            $stmt = $mysqli->prepare('SELECT * FROM Accounts WHERE account_id = ?');
            $stmt->bind_param('i', $identifier);
            $stmt->execute();
            $stmt->store_result();

            $accounts = null;
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($account_id, $username, $uuid, $rank, $coins, $credits, $xp, $achievements, $clan);

                while ($stmt->fetch()) {
                    $this->account_id = $account_id;
                    $this->username = $username;
                    $this->uuid = $uuid;
                    $this->rank = $rank;
                    $this->coins = $coins;
                    $this->credits = $credits;
                    $this->xp = $xp;
                    $this->achievements = $achievements;
                    $this->clan = $clan;
                }
            }
        } else {
            if (is_string($identifier)) {
                $stmt = $mysqli->prepare('SELECT * FROM Accounts WHERE uuid = ?');
                $stmt->bind_param('s', $identifier);
                $stmt->execute();
                $stmt->store_result();

                $accounts = null;
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($account_id, $username, $uuid, $rank, $coins, $credits, $xp, $achievements, $clan);

                    while ($stmt->fetch()) {
                        $this->account_id = $account_id;
                        $this->username = $username;
                        $this->uuid = $uuid;
                        $this->rank = $rank;
                        $this->coins = $coins;
                        $this->credits = $credits;
                        $this->xp = $xp;
                        $this->achievements = $achievements;
                        $this->clan = $clan;
                    }

                    $this->null = false;
                }
            }
        }

        $mysqli->close();
    }

    public function create($username, $uuid, $rank, $coins, $credits, $xp, $achievements, $clan)
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('INSERT INTO Accounts (username, uuid, rank, coins, credits, xp, achievements, clan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssiiiiss', $username, $uuid, $rank, $coins, $credits, $xp, $achievements, $clan);
        $stmt->execute();

        $mysqli->close();
    }

    public function update()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('UPDATE Accounts SET username = ?, uuid = ?, rank = ?, coins = ?, credits = ?, xp = ?, achievements = ?, clan = ? WHERE account_id = ?');
        $stmt->bind_param('sssiiissi', $this->username, $this->uuid, $this->rank, $this->coins, $this->credits, $this->xp, $this->achievements, $this->clan, $this->account_id);
        $stmt->execute();

        $mysqli->close();
    }

    public function delete()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        $stmt = $mysqli->prepare('DELETE FROM Accounts WHERE account_id = ?');
        $stmt->bind_param('i', $this->account_id);
        $stmt->execute();

        $mysqli->close();
    }
}