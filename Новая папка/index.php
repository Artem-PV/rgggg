<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false];

    $formType = $_POST['form_type'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($username) || empty($password) || ($formType === 'signup' && empty($email))) {
        $response['message'] = 'Заполните все поля.';
        echo json_encode($response);
        exit;
    }

    // Путь к JSON-файлу
    $file = 'users.json';

    // Загружаем текущих пользователей
    $users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    if (!is_array($users)) $users = [];

    // Регистрация
    if ($formType === 'signup') {
        // Проверка на дублирование логина/email
        foreach ($users as $existing) {
            if ($existing['username'] === $username) {
                $response['message'] = 'Пользователь с таким логином уже существует.';
                echo json_encode($response);
                exit;
            }
            if ($existing['email'] === $email) {
                $response['message'] = 'Пользователь с таким email уже зарегистрирован.';
                echo json_encode($response);
                exit;
            }
        }

        $newUser = [
            'username' => $username,
            'password' => $password, 
            'email' => $email,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        $users[] = $newUser;

        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $response['success'] = true;
        $response['user'] = $newUser;
        echo json_encode($response);
        exit;
    }

    // Авторизация
    if ($formType === 'login') {
        foreach ($users as $existing) {
            if ($existing['username'] === $username && $existing['password'] === $password) {
                $response['success'] = true;
                $response['user'] = $existing;
                echo json_encode($response);
                exit;
            }
        }
        $response['message'] = 'Неверный логин или пароль.';
        echo json_encode($response);
        exit;
    }

    $response['message'] = 'Неверный тип формы.';
    echo json_encode($response);
    exit;
}
?>
