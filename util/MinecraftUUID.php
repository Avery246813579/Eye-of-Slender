<?php

class ProfileUtils{
    public static function getProfile($identifier, $timeout = 5){
        if (strlen($identifier) <= 16) {
            $identifier = ProfileUtils::getUUIDFromUsername($identifier, $timeout);
            $url = "https://sessionserver.mojang.com/session/minecraft/profile/" . $identifier['uuid'];
        } else {
            $url = "https://sessionserver.mojang.com/session/minecraft/profile/" . $identifier;
        }
        $ctx = stream_context_create(
            array(
                'http' => array(
                    'timeout' => $timeout
                )
            )
        );
        $ret = file_get_contents($url, 0, $ctx);
        if (isset($ret) && $ret != null && $ret != false) {
            $data = json_decode($ret, true);
            return new MinecraftProfile($data['name'], $data['id'], $data['properties']);
        } else {
            return null;
        }
    }

    /**
     * @param int $timeout http timeout in seconds
     * @param $username string Minecraft username.
     * @return array (Key => Value) "username" => Minecraft username (properly capitalized) "uuid" => Minecraft UUID
     */
    public static function getUUIDFromUsername($username, $timeout = 5)
    {
        if (strlen($username) > 16)
            return array("username" => "", "uuid" => "");
        $url = 'https://api.mojang.com/profiles/page/1';
        $options = array(
            'http' => array(
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => '{"name":"' . $username . '","agent":"minecraft"}',
                'timeout' => $timeout
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
// Verification
        if (isset($result) && $result != null && $result != false) {
            $ress = json_decode($result, true);
            $ress = $ress["profiles"][0];
            $res = Array("username" => $ress['name'], "uuid" => $ress['id']);
            return $res;
        } else
            return null;
    }

    /**
     * @param $uuid string UUID to format
     * @return string Properly formatted UUID (According to UUID v4 Standards xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx WHERE y = 8,9,A,or B and x = random digits.)
     */
    public static function formatUUID($uuid)
    {
        $uid = "";
        $uid .= substr($uuid, 0, 8) . "-";
        $uid .= substr($uuid, 8, 4) . "-";
        $uid .= substr($uuid, 12, 4) . "-";
        $uid .= substr($uuid, 16, 4) . "-";
        $uid .= substr($uuid, 20);
        return $uid;
    }
}