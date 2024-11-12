<?php

// Определяем путь к директории 
$directory = __DIR__ . '/datafiles';

// Создаём массив для файлов соответствующих условию
$matchingFiles = [];

//Проверка существования папки /datafiles
if (is_dir($directory)) {
    $files = scandir($directory);
    foreach ($files as $file) {
        // Отфильтруем файлы с латинскими буквами и цифрами, и расширением .ixt
        if (preg_match("/^[a-zA-Z0-9]+\.ixt$/", $file)) {
            $matchingFiles[] = $file;
        }
    }
    // Отсортируем файлы в алфавитном порядке
    sort($matchingFiles);
    // Выведем список найденных файлов
    foreach ($matchingFiles as $matchingFile) {
        echo $matchingFile . PHP_EOL;
    }
} else {
    echo "Папка /datafiles не найдена." . PHP_EOL;
}
