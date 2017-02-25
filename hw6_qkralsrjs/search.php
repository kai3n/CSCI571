<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<script type="text/javascript">
    document.getElementById("keyword").setCustomValidity("aa");

</script>
<body>
    <div class="top">Facebook Search</div>
    <div class="middle">
        <form name="searchForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            <div class="factor">Keyword
                <input type="text"
                       id="keyword"
                       name="keyword"
                       oninvalid="this.setCustomValidity('This can\'t be left empty')"
                       oninput="this.setCustomValidity('')"
                       required>
            </div><br>
            <div class="factor">
                Type
                <select name="searchType">
                    <option value="user">Users</option>
                    <option value="page">Pages</option>
                    <option value="event">Events</option>
                    <option value="group">Groups</option>
                    <option value="place">Places</option>
                </select>
            </div><br>
            <div class="factor">Location<input type="text" name="location"></div><br>
            <div class="factor">Distance(meters)<input type="text" name="distance"></div><br>
            <input type="submit" name="search" value="Search">
            <input type="reset" name="clear" value="Clear">
        </form>
    </div>
    <div class="bottom">

        <!-- facebook API: post request-->
        <?php
            if ($_SERVER["REQUEST_METHOD"] == POST){
                echo "<table><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
                $q = "q=";
                $type = "type=";
                $fields = "fields=id,name,picture.width(700).height(700)";
                $access_token = "access_token=EAACEdEose0cBAEPs9szR6zNdktY414kSvo6itIAYqY2QChuNELGBDPpm9ruRqcEtE6w44itJPhtgpX12w2GZCiZC9DiJKlDSPVqRZC9EZCwTK3jp9Fy0amPzhEkdBlCZCLTHYsdvptZCi9EHIEj8vg3ErjwGv4HtfiJZAimaejVDLQKVE11Miq6g4ECZBqAioucZD";
                $url= "https://graph.facebook.com/v2.8/search?";

                if($_POST[searchType] == "place"){

                    $google_access_token = "AIzaSyAT1QKwxisgM8fQypxReHmnxJ2gPTPdB90";
                    $address = $_POST['location'];
                    $google_url = "https://maps.googleapis.com/maps/api/geocode/json?address=". $address . "&key=" . $google_access_token;
                    $google_url = str_replace(' ','%20', $google_url);
                    $google_response = file_get_contents($google_url);
                    $google_json_data = json_decode($google_response);
                    $lat = $google_json_data -> results[0] -> geometry -> location -> lat;
                    $lng = $google_json_data -> results[0] -> geometry -> location -> lng;

                    $center = "center=" . $lat ."," . $lng;
                    $distance = "distance=" . $_POST["distance"];
                    $url .= $q . $_POST["keyword"] . "&" . $type . $_POST[searchType] . "&" . $center . "&" . $distance . "&" . $fields . "&" . $access_token;
                }
                else{
                    $url .= $q . $_POST["keyword"] . "&" . $type . $_POST[searchType] . "&" . $fields . "&" . $access_token;
                }

                $url = str_replace(' ','%20', $url);
                $response = file_get_contents($url);
                $jsonData = json_decode($response);
                foreach($jsonData -> data as $each){
                    echo "<tr>";
                    echo "<td>" . "<a href='" . $each -> picture -> data -> url . "'><img src='" . $each -> picture -> data -> url . "' height=30px width=40px></a>" . "</td>";
                    echo "<td>" . $each -> name . "</td>";
                    echo "<td>" . "<a href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $each -> id . "'>Details</a>" . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>

        <!-- facebook API: click detail-->
        <?php
            if (isset($_GET['id'])){
                echo "<table><tr><th>Albums</th></tr>";
                $fields = "?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2) {name, picture}},posts.limit(5)";
                $access_token = "access_token=EAACEdEose0cBAEPs9szR6zNdktY414kSvo6itIAYqY2QChuNELGBDPpm9ruRqcEtE6w44itJPhtgpX12w2GZCiZC9DiJKlDSPVqRZC9EZCwTK3jp9Fy0amPzhEkdBlCZCLTHYsdvptZCi9EHIEj8vg3ErjwGv4HtfiJZAimaejVDLQKVE11Miq6g4ECZBqAioucZD";
                $url= "https://graph.facebook.com/v2.8/" . $_GET['id'] . $fields . "&" . $access_token;
                $url = str_replace(' ','%20', $url);
                $response = file_get_contents($url);
                $jsonData = json_decode($response);
                if (isset($jsonData -> albums -> data)){
                    foreach($jsonData -> albums -> data as $each){
                        echo "<tr><td>" . $each -> name . "</td></tr>";
                        echo "<tr>";
                        foreach($each -> photos -> data as $data){
                            echo "<td>" . "<a href='" . $data -> picture . "'><img src='" . $data -> picture . "' height=80px width=80px></a>" . "</td>";
                        }
                        echo "</tr>";
                    }
                }
                echo "<table><tr><th>Message</th></tr>";
                if (isset($jsonData -> posts -> data)){
                    foreach($jsonData -> posts -> data as $each){
                        echo "<tr><td>" . $each -> message . "</td></tr>";
                    }
                }
                echo "</table>";
            }
        ?>
    </div>

</body>
</html>