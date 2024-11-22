<?php
$db = require './common.php';
require 'nav.php';
//https://www.oryoy.com/news/shi-yong-php-shi-xian-duo-yong-hu-jiao-se-deng-lu-jie-mian-qie-huan-yu-quan-xian-guan-li.html?utm_source=chatgpt.com
$current_user = $_SESSION['user'];
$current_role = $_SESSION['role'];
?>
<style>
    body {
        font-family: 'Poppins', Arial, sans-serif;
        background-color: #f4f4f4; /* 背景颜色 */
        margin: 0;
        padding: 0;
    }

    .container {
        width: 400px;
        height: 400px; /* 设置为正方形 */
        background-color: #ffffff;
        padding: 20px;
        border-radius: 20px; /* 圆角 */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* 添加阴影 */
        text-align: center;
        margin: 20px auto; /* 保持居中 */
    }

    .container h2 {
        margin: 0 0 20px;
        font-size: 24px;
        color: #333;
    }

    .container p {
        font-size: 16px;
        color: #666;
    }

    .navbar {
        background-color: #9575cd; /* 替换导航栏颜色 */
        font-family: 'Roboto', sans-serif; /* 更改字体 */
        padding: 10px 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar a {
        color: #fff;
        padding: 10px 15px;
        text-decoration: none;
        font-size: 14px;
        font-weight: bold;
        transition: background-color 0.3s, box-shadow 0.3s;
        border-radius: 5px;
    }

    .navbar a:hover {
        background-color: #7e57c2; /* 悬停时的颜色 */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .navbar .actions a {
        font-size: 14px;
        font-weight: bold;
        padding: 8px 15px;
    }

    .navbar .actions a.logout {
        background-color: #f44336;
    }

    .navbar .actions a.logout:hover {
        background-color: #d32f2f;
    }
</style>
<div class="container">
    <h2>Welcome to the Traffic Police System</h2>
    <p>Hello, <?php echo $current_user; ?>! You are logged in as <?php echo $current_role; ?>.</p>
</div>
