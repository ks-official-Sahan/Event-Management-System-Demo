<?php
class Admin
{
    public static function create($firstName, $lastName, $email, $password, $mobileNo, $username)
    {
        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'mobile_no' => $mobileNo,
            'username' => $username
        ];
        return Database::getInstance()->insert('admins', $data);
    }

    public static function getById($adminId)
    {
        $result = Database::getInstance()->search('admins', ['*'], "id = $adminId");
        return ($result && count($result) > 0) ? $result[0] : null;
    }

    public static function update($adminId, $data)
    {
        return Database::getInstance()->update('admins', $data, "id = $adminId");
    }
}