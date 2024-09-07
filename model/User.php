<?php
class User
{
    public static function create($firstName, $lastName, $username, $email, $password, $dob, $line1, $line2, $line3, $zipcode, $province, $mobileNo)
    {
        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'dob' => $dob,
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'zipcode' => $zipcode,
            'province' => $province,
            'mobile_no' => $mobileNo
        ];
        return Database::getInstance()->insert('users', $data);
    }

    public static function getById($userId)
    {
        $result = Database::getInstance()->search('users', ['*'], "id = $userId");
        return ($result && count($result) > 0) ? $result[0] : null;
    }
    // ... other User model methods ...
}