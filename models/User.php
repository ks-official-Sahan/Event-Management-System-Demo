<?php
class User
{
    public static function create($firstName, $lastName, $username, $email, $password, $mobileNo)
    {
        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'mobile_no' => $mobileNo
        ];
        return Database::getInstance()->insert('users', $data);
    }

    public static function getById($userId)
    {
        $result = Database::getInstance()->search('users', ['*'], "id = $userId");
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return ($data && count($data) > 0) ? $data[0] : null;
    }

    public static function update($userId, $data)
    {
        return Database::getInstance()->update('users', $data, "id = $userId");
    }

    public static function getAll()
    {
        return Database::getInstance()->search('users', ['*']);
    }
}