<?php

final class Init
{
    /**
     * @var mysqli Соединение с базой данных
     */
    private $db;

    /**
     * Конструктор класса.
     * При создании объекта выполняет методы create() и fill().
     *
     * @param string $host Хост базы данных
     * @param string $username Имя пользователя бд
     * @param string $password Пароль пользователя бд
     * @param string $dbname Имя базы данных
     */
    public function __construct($host, $username, $password, $dbname)
    {
        $this->db = new mysqli($host, $username, $password, $dbname);

        // Проверка соединения
        if ($this->db->connect_errno) {
            echo "Ошибка соединения: " . $this->db->connect_error;
            exit();
        }
        // Выполнение методов создания и заполнения таблицы
        $this->create();
        $this->fill();
    }

    /**
     * Метод create
     *
     * Создает таблицу 'test' с пятью полями, если она не существует.
     * Метод доступен только внутри класса.
     *
     * @return void
     */
    private function create()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS test (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(50),
                description TEXT,
                created_at CURRENT_TIMESTAMP,
                updated_at CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                result ENUM('normal', 'success', 'fail') DEFAULT 'normal'
            )
        ";

        if (!$this->db->query($sql)) {
            die("Ошибка при создании таблицы: " . $this->db->error);
        }
    }

    /**
     * Метод fill
     *
     * Заполняет таблицу 'test' случайными данными.
     * Метод доступен только внутри класса.
     *
     * @return void
     */
    private function fill()
    {
        $names = ['Alex', 'Marta', 'Anton', 'Dima', 'Nick'];
        $results = ['normal', 'success', 'fail'];

        foreach ($names as $name) 
        {
            $description = 'Some description for ' . $name;
            $result = $results[array_rand($results)];

            $sql = "
                INSERT INTO test (name, description, result)
                VALUES ('$name', '$description', '$result')
            ";

            if (!$this->db->query($sql)) {
                die("Ошибка вставки данных в таблицу: " . $this->db->error);
            }
        }
    }

    /**
     * Метод get
     *
     * Выбирает данные из таблицы 'test' по критерию 'result', где 'result' может быть 'normal' или 'success'.
     * Метод доступен для вызова извне класса.
     *
     * @param string $result Значение поля 'result' для фильтрации ('normal' или 'success')
     * @return array Результаты выборки
     */
    public function get($result)
    {
        // Проверка допустимости значения result
        if (!in_array($result, ['normal', 'success'])) {
            die("Недопустимое значение результата.");
        }

        $sql = "SELECT * FROM test WHERE result = '$result'";
        $result_set = $this->db->query($sql);

        if (!$result_set) {
            die("Ошибка при выборки данных: " . $this->db->error);
        }

        $data = [];
        while ($row = $result_set->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Деструктор класса.
     * Закрывает соединение с базой данных при удалении объекта.
     */
    public function __destruct()
    {
        $this->db->close();
    }
}

