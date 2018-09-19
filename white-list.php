<?php
$mysqli = connect_db();

$sql = "INSERT INTO `whitelist` (`firstname`, `lastname`, `email`, `country`, `tokens`) 
        VALUES ('".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['email']."', '".$_POST['country']."', '".$_POST['tokens']."')";
echo $sql;
if (!$mysqli->query($sql)) {
    echo("error recovery bd");
    exit;
}

$mysqli->close();

$email_to = 'kk3snet@gmail.com';
$email_subject = 'New subscriber whitelist opnplatform';
$email_body = 'New subscriber:<br>
    firstname: ' . $_POST['firstname'] . '<br>
    lastname: ' . $_POST['lastname'] . '<br>
    email: ' . $_POST['email'] . '<br>
    country: ' . $_POST['country'] . '<br>
    tokens: ' . $_POST['tokens'] . '<br>
';

send_email($email_to, $email_subject, $email_body);

$email_to = $_POST['email'];
$email_subject = 'opnplatform whitelist';
$email_body = $_POST['firstname'] . ', you have been added to the Whitelist OPN.<br>';

send_email($email_to, $email_subject, $email_body);

function connect_db() {
    $mysqli = new mysqli("localhost", "opn.new.age", "lvu8Vsfk8NIeGrjTfKCS", "whitelist");

    $mysqli->set_charset("utf8");

    if ($mysqli->connect_errno) {
        echo("error connect bd");
        exit;
    }

    return $mysqli;
}

function send_email($email_to, $email_subject, $email_body){
    $api_key = "6jnh15wbr5b1ndjzmykwcqmiqyfdk6xnyfkmei6e";

    $email_from_name = 'opnplatform';
    $email_from_email = 'notifications.opnplatform@gmail.com';
    $list_id = 15226169;

// Создаём POST-запрос
    $request = [
        'api_key' => $api_key,
        'email' => $email_to,
        'sender_name' => $email_from_name,
        'sender_email' => $email_from_email,
        'subject' => $email_subject,
        'body' => $email_body,
        'list_id' => $list_id,
        'lang' => 'en',
    ];

// Устанавливаем соединение
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_URL, 'https://api.unisender.com/ru/api/sendEmail?format=json');

    $result = curl_exec($ch);

    if ($result) {
        // Раскодируем ответ API-сервера
        $jsonObj = json_decode($result);

        if (null === $jsonObj) {
            // Ошибка в полученном ответе
            echo 'Invalid JSON';

        } elseif (!empty($jsonObj->error)) {
            // Ошибка отправки сообщения
            echo sprintf('An error occured %s (code: %s)', $jsonObj->error, $jsonObj->code);
        } else {
            // Сообщение успешно отправлено
            echo 'Email message is sent. Message id ' . $jsonObj->result->email_id;

        }
    } else {
        // Ошибка соединения с API-сервером
        echo 'API access error';
    }
}