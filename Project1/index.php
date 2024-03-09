a
<?php
//database server type location, database name
$data_source_name = 'mysql:host=localhost;dbname=stocks';
//feels bad, but we don't have time to show a better way.
$username = 'stockuser';
$password = 'test';

try {
    $database = new PDO($data_source_name, $username, $password);
    echo "<p>Database connection successful</p>";

    $action = htmlspecialchars(filter_input(INPUT_POST, "action"));
    
    $symbol = htmlspecialchars(filter_input(INPUT_POST,"symbol"));
    $name = htmlspecialchars(filter_input(INPUT_POST,"name"));
    $current_price = filter_input(INPUT_POST, "current_price", FILTER_VALIDATE_FLOAT);
    
    if ( $action == "insert" && $symbol != "" && $name != "" && $current_price != 0){
        //DANGER DANGER DANGER - SQL Injection risk
        // Don't ever just plug values into a query!
        //$query = "INSERT INTO stock (symbol, name, current_price) "
        //        . "VALUES ($symbol, $name, $current_price)";
    
        //instead, use substitutions
    $query = "INSERT INTO stock (symbol, name, current_price) "
                . "VALUES (:symbol, :name, :current_price)";
    
    //value = binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":symbol", $symbol);
    $statement->bindValue(":name", $name);
    $statement->bindValue(":current_price", $current_price);
    
        $statement->execute();
    
        $statement->closeCursor();
        
} else if($action == "update" && $symbol != "" && $name != "" && $current_price != 0){
        
            $query = "update stock set name = :name, current_price = :current_price "
                    . " where symbol = :symbol";
            
            //value binding in PDO protects against sql injection
            $statement = $database->prepare($query);
            $statement->bindValue(":symbol", $symbol);
            $statement->bindValue(":name", $name);
            $statement->bindValue(":current_price", $current_price);
            
            $statement->execute();
            
            $statement->closeCursor();
            
        } else if ($action == "delete" && $symbol != "" ){
            $query = "delete from stock "
                    . "where symbol = :symbol";
            
            //value binding in PDO protects against sql injection
            $statement = $database->prepare($query);
            $statement->bindValue(":symbol", $symbol);
            
            $statement->execute();

            
            $statement->closeCursor();
        }
        else if ($action != "" ){
            echo "<p>Missing symbol, name, or current price </p>";
        }
        
        
        $query = 'SELECT symbol, name, current_price, id FROM stock';
        
        //prepare the query please
        $statement = $database->prepare($query);
        
        //run the query please
        $statement->execute();

         //this might be risky if you have HUGE amounts of data
        $stock = $statement->fetchAll();
        
        $statement->closeCursor();
    
} catch (Exception $ex) {
    $error_message = $e->getMessage();
    echo "<p>Error message: $error_message </p>";
}
            ?>
            
<!doctype html>
<html>
    <head>
        <title>

        </title>
    </head>
    <body>
        <table>
        <tr>
            <th>symbol</th>
            <th>name</th>
            <th>current_price</th>
            <th>id </th>
        </tr>
        <?php foreach($stock as $stocks) : ?>
        <tr>
                <td><?php echo $stocks['symbol']; ?></td>
                <td><?php echo $stocks['name']; ?></td>
                <td><?php echo $stocks['current_price']; ?></td>
                <td><?php echo $stocks['id']; ?></td>
        </tr>    
        
        <?php endforeach; ?>
        </table>

        </br>
        <h2>Add Stock</h2>
        <form action="index.php" method="post">
            <label>Symbol:</label>
            <input type="text" name="symbol"/><br>
            <label>Name:</label>
            <input type="text" name="name"/><br>
            <label>Current Price:</label>
            <input type="text" name="current_price"/><br>
            <input type="hidden" name='action' value='insert'/>
            <label&nbsp;</label>
            <input type="submit" value="Add Stock"/>
        </form>
        </br>
        
        <h2>Update Stocks</h2>
        <form action="index.php" method=post">
            <label>Symbol:</label>
            <input type="text" name="symbol"/><br>
           <label>Name:</label> 
            <input type="text" name="name"/><br>
            <label>Current Price:</label>
            <input type="text" name="current_price"/><br>
            <input type="hidden" name='action' value='update'/><br>
            <label>&nbsp;</label>
            <input type="submit" value="Update Stock"/>
        </form>
        
        <h2>Delete Stocks</h2>
        <form action="index.php" method="post"/>
        <label>Symbol:</label>
        <input type="text" name="symbol"/><br>
        <input type="hidden" name='action' value='delete'/>
        <label>&nbsp;</label>
        <input type="submit" value="Delete Stock"/>
    </form>
    
    </body>
</html>

