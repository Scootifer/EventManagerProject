<?php
session_start();


$host = 'localhost';
$db = 'userdb';
$user = 'server';
$pass = 'server';
$conn = new mysqli($host, $user, $pass, $db);

// Hashtable of joined Universities
$userJoinedUniv_HT = array();


if(isset($_POST['join'])) {
    $sql = "INSERT INTO universitymembers(UnivID, UserID) VALUES (" . $_POST['univ_id'] . ", ". $_SESSION['UserID'] . ");";
    $result = $conn->query($sql);
    header("Refresh:0");
}

if(isset($_POST['leave'])) {
    $sql = "DELETE FROM universitymembers as um WHERE um.UnivID=". $_POST['univ_id'] . " AND um.UserID=" . $_SESSION['UserID'];
    $result = $conn->query($sql);
    header("Refresh:0");
}

if(isset($_POST['delete'])) {
    $sql = "DELETE FROM universities as u WHERE u.UnivID=". $_POST['univ_id'];
    $result = $conn->query($sql);
    header("Refresh:0");
}

if(isset($_POST['addUniv'])) {
    $sql = "INSERT INTO universities(UnivName) VALUES (\"" . $_POST['new_univ_name'] . "\");"; 
    $result = $conn->query($sql);
    header("Refresh:0");
}

if(isset($_POST['join_new_univ'])){
    $_SESSION['join_new_univ'] = 1;
}

if(isset($_POST['close_new_univ'])) {
    unset($_SESSION['join_new_univ']);
    header("Refresh:0");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <link rel="stylesheet" type="text/css" href="css/homepage.css">
</head>
<h1>

</h2>
<body>
    <!--Dynamic universities table -->
    <?php 

        $sql = "SELECT users.IsAdmin FROM users WHERE users.UserID = " . $_SESSION['UserID'];
        $res = $conn->query($sql);
        $resultAdmin = $res->fetch_assoc()['IsAdmin'];
        
        //
        // Universities Table
        //

        

        //query universities this user is a part of
        $sql = "SELECT um.UnivID, um.UserID FROM universitymembers as um WHERE um.UserID = " . $_SESSION['UserID'];
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $userJoinedUniv_HT[$row['UnivID']] = 1;
            }
        }
        
        
        //query universities this user is a part of
        $sql = "SELECT universities.UnivID, universities.UnivName FROM universities";
        $resultUniversities = $conn->query($sql);

        echo "<div class=\"TopTables\">";
        echo "<table>";
        echo "<tr><th>Your Universities</th><th><td><form method=\"POST\" action=\"homepage.php\"><input type=\"submit\" name=\"join_new_univ\" value=\"Join Universities\"> </form></td></th></tr>\n";

        if($resultUniversities->num_rows > 0) {

            //process university list query
            while($row = $resultUniversities->fetch_assoc()) {

                if( isset($userJoinedUniv_HT[ $row['UnivID'] ] ) ) {
                    echo "<td>" . $row['UnivName'] . "</td> \n";
                    echo "<td><form method=\"POST\" action=\"homepage.php\">
                    <input type=\"hidden\" name=\"univ_id\" value=\"". $row['UnivID'] ."\">
                    <input type=\"submit\" name=\"leave\" value=\"Leave\"> 
                    </form></td>\n";
            
                    if($resultAdmin) {
                       echo "<td><form method=\"POST\" action=\"homepage.php\">
                       <input type=\"hidden\" name=\"univ_id\" value=\"". $row['UnivID'] ."\">
                        <input type=\"submit\" name=\"delete\" value=\"Delete\"> 
                        </form></td> \n</tr>";
                    } else echo "</tr>";

                }
            }   

        } 
        else {
            echo "<tr><td>No Universities Found</td></tr>";   
        }
    
        

        echo "<tr><td>Click Join to find universities!</td></tr>";
        echo "</table>\n";

        // JOINING A NEW UNIVERSITY
        //
        if(isset($_SESSION['join_new_univ'])) {

            echo "<table>";
            echo "<tr><th>Search Universities</th><th><td><form method=\"POST\" action=\"homepage.php\"><input type=\"submit\" name=\"close_new_univ\" value=\"Close\"> </form></td></th></tr>\n";

            mysqli_data_seek($resultUniversities, 0);
            if($resultUniversities->num_rows > 0) {

                while($row = $resultUniversities->fetch_assoc()) {
                    //process university list query
                    if( !isset( $userJoinedUniv_HT[ $row['UnivID'] ] ) ) {
    
                        echo "<tr><td>" . $row['UnivName'] . "</td> \n";
                        echo "<td><form method=\"POST\" action=\"homepage.php\">
                            <input type=\"hidden\" name=\"univ_id\" value=\"". $row['UnivID'] ."\">
                            <input type=\"submit\" name=\"join\" value=\"Join\"> 
                            </form></td></tr>\n";
                    }
                }
                echo "</table>";
            }
            else {
                echo "<tr><td>No Universities Found</td></tr>";
                echo "</table>";
                
            }    
                

        }

        echo "</div>\n";

        
        echo "</div class=\"Events\">\n";



        echo "</div>\n";

        echo "<div class=\"Calendar\">\n";
        require('php/Calendarize.php');
        
        echo "<button onclick=\"window.location.href='logout.php'\">Logout</button>";
        echo "</div>\n";
    
    ?>

    


</body>
</html>

<?php 
    $conn->close();
?>
