<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<style>
    table, th, td {
        border: 1px solid black;
    }
</style>
<script type="text/javascript">
    function show() {
        var location = document.getElementById("location");
        var distance = document.getElementById("distance");
        if(document.getElementById('searchType').selectedIndex == "4" ) {
            location.style.display = "block";
            distance.style.display = "block";
        }
        else {
            location.style.display = "none";
            distance.style.display = "none";
        }
	}

	function toggle(id) {
        var e = document.getElementById(id);
        if(e.style.display == "none")
            e.style.display = "block";
        else
            e.style.display = "none";
    }

    function toggle_class(cls) {
        var eArray = document.getElementsByClassName(cls);
        for(var i = 0; i < eArray.length; i++){
            if(eArray[i].style.display == "none")
                eArray[i].style.display = "block";
            else
                eArray[i].style.display = "none";

        }
    }

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
                <select onchange="show();" id="searchType" name="searchType">
                    <option value="user">Users</option>
                    <option value="page">Pages</option>
                    <option value="event">Events</option>
                    <option value="group">Groups</option>
                    <option value="place">Places</option>
                </select>
            </div><br>
            <div class="factor" id="location" style="display: none;">Location<input type="text" name="location"></div><br>
            <div class="factor" id="distance" style="display: none;">Distance(meters)<input type="text" name="distance"></div><br>
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
                $access_token = "access_token=EAAVAONT3U3MBALHbtZCREhQ9yZBOK4ZCrsHoamVZC6bVppddAZBvXSmZAl2zf1w0XX2SeueLZBmZA6C9JQmm0yvIPRo3ULtexVttcHRbzqVSbO5B7PhwDJYP9SVhhG0FHZCfqLVS9Wn4QX1PYmOONffLyRIGOxQg3B77k9kAwIRDFRGUoqEU9VlA4Bm1daTj9eZCUZD";
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
                echo "<table><tr><th><p onclick=" . "toggle('albums');" . ">Albums</p></th></tr></table>";
                $fields = "?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2) {name, picture}},posts.limit(5)";
                $access_token = "access_token=EAAVAONT3U3MBALHbtZCREhQ9yZBOK4ZCrsHoamVZC6bVppddAZBvXSmZAl2zf1w0XX2SeueLZBmZA6C9JQmm0yvIPRo3ULtexVttcHRbzqVSbO5B7PhwDJYP9SVhhG0FHZCfqLVS9Wn4QX1PYmOONffLyRIGOxQg3B77k9kAwIRDFRGUoqEU9VlA4Bm1daTj9eZCUZD";
                $url= "https://graph.facebook.com/v2.8/" . $_GET['id'] . $fields . "&" . $access_token;
                $url = str_replace(' ','%20', $url);
                $response = file_get_contents($url);
                $jsonData = json_decode($response);
                echo "<div id='albums'>";
                if (isset($jsonData -> albums -> data)){
                    $cnt = 1;
                    foreach($jsonData -> albums -> data as $each){
                        echo "<div onclick=toggle_class('album" . $cnt . "')>" . $each -> name . "</div>";

                        foreach($each -> photos -> data as $data){
                            echo "<div class='album" . $cnt . "'>" . "<a href='" . $data -> picture . "'><img src='" . $data -> picture . "' height=80px width=80px></a>" . "</div>";
                        }

                        $cnt++;
                    }
                }
                echo "</div>";
                echo "<table><tr><th><p onclick=" . "toggle('posts');" . ">Message</p></th></tr></table>";
                echo "<table id='posts'>";
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