<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<style>
    html{
        font-family: Helvetica, Arial, sans-serif;
    }
    fb{
        font-size:25px;
    }

    th{
        color: #fff;
        background-color: #4267b2;
        border: 1px solid #aaa;
        text-align: left;
        padding: 0px
    }
    td{
        border: 1px solid #aaa;
        text-align: left;
        padding: 0px
    }
    table{
        width: 100%;
        border: 1px solid #aaa;
        border-spacing: 0;

    }

    div#bottom{
        border: 1px;
        margin: auto;
        text-align: left;
        width: 70%;

    }
    .data{
        border: 1px solid #aaa;
    }
    .top{
        color: #222;
        background-color: #4267b2;
        border: 2px solid #aaa;
        margin: auto;
        text-align: center;
        width: 50%;
    }
    hr{
    width:90%;
    }
    #ab, #msg{
        color: #fff;
        background-color: #4267b2;
        text-align: center;

    }
    #no_ab, #no_msg{
        color: #fff;
        background-color: #4267b2;
        text-align: center;

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

	function toggle_id(id) {
        var e = document.getElementById(id);
        if(id == "albums")
            document.getElementById('posts').style.display = "none";
        if(id == "posts")
            document.getElementById('albums').style.display = "none";
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

    function clear_doc(){
        window.location.href = './search.php';
    }

</script>
<body onload="show();">
    <br><br>
    <div class="top"><br><fb>Facebook Search</fb><hr>
        <form name="searchForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            <div class="factor">Keyword
                <input type="text"
                       id="keyword"
                       name="keyword"
                       oninvalid="this.setCustomValidity('This can\'t be left empty')"
                       oninput="this.setCustomValidity('')"
                       value="<?php if (isset($_POST['keyword'])) echo $_POST['keyword']; ?>"
                       required>
            </div><br>
            <div class="factor">
                Type
                <select onchange="show();" id="searchType" name="searchType">
                    <option <?php if (isset($_POST['searchType']) && $_POST['searchType']=="user") echo "selected";?> value="user">Users</option>
                    <option <?php if (isset($_POST['searchType']) && $_POST['searchType']=="page") echo "selected";?> value="page">Pages</option>
                    <option <?php if (isset($_POST['searchType']) && $_POST['searchType']=="event") echo "selected";?> value="event">Events</option>
                    <option <?php if (isset($_POST['searchType']) && $_POST['searchType']=="group") echo "selected";?> value="group">Groups</option>
                    <option <?php if (isset($_POST['searchType']) && $_POST['searchType']=="place") echo "selected";?> value="place">Places</option>
                </select>
            </div><br>
            <div class="factor" id="location" style="display: none;">Location <input type="text" name="location" value="<?php if (isset($_POST['location'])) echo $_POST['location']; ?>"></div><br>
            <div class="factor" id="distance" style="display: none;">Distance(meters) <input type="text" name="distance" value="<?php if (isset($_POST['distance'])) echo $_POST['distance']; ?>"></div><br>
            <input type="submit" name="search" value="Search">
            <input type="button" onclick=clear_doc() name="clear" value="Clear">
        </form>
        <br>
    </div>
    <br><br><br><br>
    <div id="bottom">

        <!-- facebook API: post request-->
        <?php
            if ($_SERVER["REQUEST_METHOD"] == POST){
                echo "<table class='result_list'><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
                $q = "q=";
                $type = "type=";
                $fields = "fields=id,name,picture.width(700).height(700)";
                $access_token = "access_token=EAACEdEose0cBAA2jaPaO39rgppPCx6tlPdxWct9FYhWJxyaULE9NaWSyFYGmsEmYVXCtkZBlpjjr1ZBstFGdUn5dtLJZB294K8QzO2L5k6eaxzdJbByi6C7GuBoDNJAVOz2U8MVdiQf8ZCoajZC3zGuA8hDqjJNKT2wWUrdN0WNyzQZBLJuITcEyg8WdFxHZCIZD";
                $url= "https://graph.facebook.com/v2.8/search?";

                if($_POST[searchType] == "place"){
                    if($_POST['location'] != ""){
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
                    else
                        $url .= $q . $_POST["keyword"] . "&" . $type . $_POST[searchType] . "&" . $fields . "&" . $access_token;
                }
                else{
                    $url .= $q . $_POST["keyword"] . "&" . $type . $_POST[searchType] . "&" . $fields . "&" . $access_token;
                }

                $url = str_replace(' ','%20', $url);
                $response = file_get_contents($url);
                $jsonData = json_decode($response);
                foreach($jsonData -> data as $each){
                    echo "<tr>";
                    echo "<td>" . "<a target='_blank' href='" . $each -> picture -> data -> url . "'><img src='" . $each -> picture -> data -> url . "' height=30px width=40px></a>" . "</td>";
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
                $fields = "?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2) {name, picture}},posts.limit(5)";
                $access_token = "access_token=EAACEdEose0cBAA2jaPaO39rgppPCx6tlPdxWct9FYhWJxyaULE9NaWSyFYGmsEmYVXCtkZBlpjjr1ZBstFGdUn5dtLJZB294K8QzO2L5k6eaxzdJbByi6C7GuBoDNJAVOz2U8MVdiQf8ZCoajZC3zGuA8hDqjJNKT2wWUrdN0WNyzQZBLJuITcEyg8WdFxHZCIZD";
                $url= "https://graph.facebook.com/v2.8/" . $_GET['id'] . $fields . "&" . $access_token;
                $url = str_replace(' ','%20', $url);
                $response = file_get_contents($url);
                $jsonData = json_decode($response);

                if (isset($jsonData -> albums -> data)){
                    $cnt = 1;
                    echo "<div id='ab' class='data' onclick=" . "toggle_id('albums');" . ">Albums</div>";
                    echo "<div class='data' id='albums' style='display:none'>";
                    foreach($jsonData -> albums -> data as $each){
                        echo "<div class='data' onclick=toggle_class('album$cnt')>" . $each -> name . "</div>";
                        echo "<span class='album$cnt' style='display:none'>";
                        foreach($each -> photos -> data as $data){
                            echo "<a target='_blank' href='" . $data -> picture . "'><img src='" . $data -> picture . "' height=80px width=80px></a>";
                        }
                        echo "</span>";
                        $cnt++;
                    }
                    echo "</div>";
                }
                else
                    echo "<div id=no_ab>No Albums</div><br>";

                if (isset($jsonData -> posts -> data)){
                    echo "<div id='msg' class='data' onclick=" . "toggle_id('posts');" . ">Message</div>";
                    echo "<div class='data' id='posts' style='display:none'>";
                    foreach($jsonData -> posts -> data as $each){
                        echo "<div class='data'>" . $each -> message . "</div>";
                    }
                }
                else
                    echo "<div id='no_msg'>No message</div><br>";

            }
        ?>
    </div>

</body>
</html>