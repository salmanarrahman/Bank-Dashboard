<?php

namespace App\Classes;
class fileOps
{


    static function getTransactionsBySenderEmail($data, $senderEmail) {
        $result = [];

        foreach ($data as $transaction) {
            if ($transaction['senderEmail'] === $senderEmail) {
                $result[] = $transaction;
            }
        }

        return $result;
    }

    static function addTransaction($data){
        $file_path = 'transaction.json';

        if (file_exists($file_path)) {

            $current_data = file_get_contents($file_path);
            $array_data = json_decode($current_data, true);
        } else {

            $array_data = [];
        }


        $array_data[] = $data;


        return file_put_contents($file_path, json_encode($array_data, JSON_PRETTY_PRINT));

    }
    static function saveUserData($user_data) {

        $file_path = 'users.json';

        if (file_exists($file_path)) {

            $current_data = file_get_contents($file_path);
            $array_data = json_decode($current_data, true);
        } else {

            $array_data = [];
        }


        $array_data[] = $user_data;


        return file_put_contents($file_path, json_encode($array_data, JSON_PRETTY_PRINT));
    }

    static function isEmailExists($email, $array_data)
    {
        foreach ($array_data as $user) {
            if (isset($user['email']) && $user['email'] === $email) {
                return true;
            }
        }
        return false;
    }
    static function returnName($email, $array_data)
    {
        foreach ($array_data as $user) {
            if (isset($user['email']) && $user['email'] === $email) {
                return $user['name'];
            }
        }
        return null;
    }
    static function checkLogin($email,$password, $array_data)
    {
        foreach ($array_data as $user) {
            if (isset($user['email']) && isset($user['password']) && $user['email'] === $email
             && $user['password'] === $password) {
                return $user["email"];
            }
        }
        return null;
    }

    static function getSingleUser($email) {
        $array_data = self::getAllUsers();
        foreach ($array_data as $user) {
            if (isset($user['email']) && $user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }





    public static function getAllUsers()
    {
        $file_path = 'users.json';

        // Check if the JSON file exists
        if (file_exists($file_path)) {
            // Get the current data
            $current_data = file_get_contents($file_path);
            return json_decode($current_data, true);
        } else {
            return []; // Return an empty array if file does not exist or has no data
        }
    }
    public static function getAllTransaction()
    {
        $file_path = 'transaction.json';

        // Check if the JSON file exists
        if (file_exists($file_path)) {
            // Get the current data
            $current_data = file_get_contents($file_path);
            return json_decode($current_data, true);
        } else {
            return []; // Return an empty array if file does not exist or has no data
        }
    }
    static function saveFeedback($uuid, $feedback) {
        $array_data = self::getAllUsers();
        $updated = false;

        foreach ($array_data as &$user) {
            if (isset($user['uuid']) && $user['uuid'] === $uuid) {
                if (!isset($user['messages'])) {
                    $user['messages'] = [];
                }
                $user['messages'][] = ['feedback' => $feedback];
                $updated = true;
                break;
            }
        }

        if ($updated) {
            $json_data = json_encode($array_data, JSON_PRETTY_PRINT);
            return file_put_contents('users.json', $json_data);
        }

        return false;
    }

    static function addUserBalance($email, $new_balance) {
        $file_path = 'users.json';
        $array_data = self::getAllUsers();
        $user_found = false;

        foreach ($array_data as &$user) {
            if (isset($user['email']) && $user['email'] === $email) {
                $user['balance'] += $new_balance;
                $user_found = true;
                break;
            }
        }

        if ($user_found) {
            return file_put_contents($file_path, json_encode($array_data, JSON_PRETTY_PRINT));
        } else {
            return false;
        }
    }

    static function withdrawUserBalance($email, $new_balance) {
        $file_path = 'users.json';
        $array_data = self::getAllUsers();
        $user_found = false;

        foreach ($array_data as &$user) {
            if (isset($user['email']) && $user['email'] === $email) {
                $user['balance'] -= $new_balance;
                $user_found = true;
                break;
            }
        }

        if ($user_found) {
            return file_put_contents($file_path, json_encode($array_data, JSON_PRETTY_PRINT));
        } else {
            return false;
        }
    }

    static function addUserBalanceAndAddTransaction($email, $amount, $type) {
        $file_path = 'users.json';
        $array_data = self::getAllUsers();
        $user_found = false;

        foreach ($array_data as &$user) {
            if (isset($user['email']) && $user['email'] === $email) {
                if (!isset($user['transaction'])) {
                    $user['transaction'] = [];
                }
                $user['transaction'][] = ['type' => $type, 'amount' => $amount];

                if ($type === 'withdraw' || $type === 'transfer') {
                    $user['balance'] -= $amount;
                } elseif ($type === 'deposit') {
                    $user['balance'] += $amount;
                }

                $user_found = true;
                break;
            }
        }

        if ($user_found) {
            return file_put_contents($file_path, json_encode($array_data, JSON_PRETTY_PRINT));
        } else {
            return false;
        }
    }


}


?>