<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Guest Book</h1>
    <form action="add_message.php" method="post">
        <label for="user_name">User Name:</label>
        <input type="text" id="user_name" name="user_name" required>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <label for="captcha">CAPTCHA:</label>
        <img src="captcha.php" alt="CAPTCHA Image">
        <input type="text" id="captcha" name="captcha" required>
        <label for="text">Message:</label>
        <textarea id="text" name="text" required></textarea>
        <input type="submit" value="Add Message">
    </form>
    <h2>Messages</h2>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=user_name">User Name</a></th>
                <th><a href="?sort=email">E-mail</a></th>
                <th><a href="?sort=created_at">Date</a></th>
                <th><a href="?sort=text">Message</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db.php';
            
            //пагинаци€
            $limit = 5; //  оличество сообщений на странице
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            //сортировка
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
            $order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';

            // «апрос на получение сообщений
            $sql = "SELECT * FROM messages ORDER BY $sort $order LIMIT $limit OFFSET $offset";
            $result = $conn->query($sql);
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td>" . htmlspecialchars($row['text']) . "</td>";
                echo "</tr>";
            }

            // «апрос на получение общего количества сообщений
            $result_count = $conn->query("SELECT COUNT(*) AS total FROM messages");
            $total_rows = $result_count->fetch_assoc()['total'];
            $total_pages = ceil($total_rows / $limit);

            $conn->close();
            ?>
        </tbody>
    </table>
    
    <!-- ѕагинаци€ -->
    <div class="pagination">
        <?php
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '&sort=' . $sort . '&order=' . strtolower($order) . '">Previous</a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo '<span class="current-page">' . $i . '</span>';
            } else {
                echo '<a href="?page=' . $i . '&sort=' . $sort . '&order=' . strtolower($order) . '">' . $i . '</a>';
            }
        }

        if ($page < $total_pages) {
            echo '<a href="?page=' . ($page + 1) . '&sort=' . $sort . '&order=' . strtolower($order) . '">Next</a>';
        }
        ?>
    </div>
</body>
</html>