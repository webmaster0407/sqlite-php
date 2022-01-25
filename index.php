<html>
    <head></head>
    <link rel='stylesheet' href = './app.css'></link>
    <body>
        <div class="index-container">
            <?php 
            require 'vendor/autoload.php';

            use App\SQLiteConnection;
            use App\Config;

            $pdo = (new SQLiteConnection())->connect();

            if ($pdo != null) {
                echo 'Connected to the SQLite database successfully!';
                echo '<br>';
                echo '<div><a href="SQLiteUpdate.php" class="index-a">Update table</a></div>';
            } else {
                echo 'Whoops, could not connect to the SQLite database!';
            }
            

            ?>
        </div>
    </body>
</html>