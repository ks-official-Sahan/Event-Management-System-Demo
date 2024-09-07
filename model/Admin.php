<?php
class Admin
{
    public static function create($title, $firstName, $lastName, $nwi, $email, $password, $mobileNo, $position)
    {
        $data = [
            'title' => $title,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'nwi' => $nwi,
            'email' => $email,
            'password' => $password,
            'mobile_no' => $mobileNo,
            'position' => $position
        ];
        return Database::getInstance()->insert('admins', $data);
    }

    // ... other Admin model methods ...
}