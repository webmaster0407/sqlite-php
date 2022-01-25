<html>
    <head>

    </head>
    <link rel='stylesheet' href = './app.css'></link>
    <body>
        <div class="update-container">
        <?php 
            require 'vendor/autoload.php';

            use App\SQLiteConnection;
            use App\Config;

            $pdo = (new SQLiteConnection())->connect();
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);


            if ($pdo != null) {
                // get table names to $tables as $table_name
                $tablesquery = $pdo->query("SELECT name FROM sqlite_master WHERE type='table';");

                $tables = [];
                foreach ( $tablesquery->fetchAll(\PDO::FETCH_ASSOC) as $table ) {
                    if ($table['name'] != "sqlite_sequence") {
                        $tables[] = $table['name'];
                    }
                }
                // end get table names

                // create table named books
                $createNewTableSql = "CREATE TABLE IF NOT EXISTS 'books' (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, 
                    title TEXT UNIQUE NOT NULL default '', 
                    imgurl TEXT NOT NULL default '', 
                    isbn TEXT NOT NULL default '', 
                    year TEXT NOT NULL default '', 
                    lang TEXT NOT NULL default '', 
                    pages TEXT NOT NULL default '', 
                    descr TEXT NOT NULL default ''
                );";
                $stmt = $pdo->prepare( $createNewTableSql);
                $stmt->execute();       
                // end create table named books

                // insert data of first table to "books" table 
                foreach( $tables as $table_name ) {
                    
                    $insertSql = "INSERT INTO books  (title, imgurl) SELECT key, content FROM '".$table_name."';";
                    $stmt = $pdo->prepare($insertSql);
                    $stmt->execute();

                    break;
                }
                // end insert data

                // drop tables 
                foreach ($tables as $table_name) {
                    
                    $dropTableSql = "DROP TABLE IF EXISTS '".$table_name."';";
                    $stmt = $pdo->prepare($dropTableSql);
                    $stmt->execute();
                }
                // end drop table

                // display data that modified
                echo 'Update table successfully!';
                echo '<br>';
                echo '<p>------Table name is <span>books</span>---------</p>';
                echo '<div><a href="index.php" class="update-a">Go back to main page</a></div>';

                $data = [];
                $stmt = $pdo->query('SELECT * FROM books');

                $rltArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                foreach (  $rltArray as $row ) {
                    $id         = $row['id'];
                    $title      = $row['title'];
                    $imgurl     = $row['imgurl'];
                    $isbn       = $row['isbn'];
                    $year       = $row['year'];
                    $lang       = $row['lang'];
                    $pages      = $row['pages'];
                    $descr      = $row['descr'];

                    $imgurl = str_replace("|", ".", $imgurl );
                    $imgurl = str_replace("\n", ".", $imgurl );

                    $data[] = [
                        'id'        => $id,
                        'title'     => $title,
                        'imgurl'    => $imgurl,
                        'isbn'      => $isbn,
                        'year'      => $year,
                        'lang'      => $lang,
                        'pages'     => $pages,
                        'descr'     => $descr
                    ];
                }

                echo '<table>
                        <tr>
                            <th>id</th>
                            <th>title</th>
                            <th>imgurl</th>
                            <th>isbn</th>
                            <th>year</th>
                            <th>lang</th>
                            <th>pages</th>
                            <th>descr</th>
                            <th>isModified</th>
                        </tr>';

                foreach($data as $row) {
                    if ( isset($row['id'])  ) {
                        $sql = 'UPDATE books '
                            .'SET imgurl = :imgurlTmp '
                            .'WHERE id = :idTmp';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':imgurlTmp', $row['imgurl']);
                        $stmt->bindValue(':idTmp', $row['id']);

                        echo '<tr><td>'
                                .$row['id']
                                .'</td><td>'
                                .$row['title']
                                .'</td><td>'
                                .$row['imgurl']
                                .'</td><td>'
                                .$row['isbn']
                                .'</td><td>'
                                .$row['year']
                                .'</td><td>'
                                .$row['lang']
                                .'</td><td>'
                                .$row['pages']
                                .'</td><td>'
                                .$row['descr']
                                .'</td><td>'
                                .json_encode($stmt->execute())
                            .'</td></tr>';
                    }
                }
                echo '</table>';
                // end display data
            } else {
                echo 'Whoops, could not connect to the SQLite database!';
            }
            ?>
        </div>
    </body>
</html>


