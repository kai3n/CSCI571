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
                    <option value="place">Places</option>
                </select>
            </div><br>
            <div class="factor">Location<input type="text" name="location"></div><br>
            <div class="factor">Distance(meters)<input type="text" name="distance"></div><br>
            <input type="submit" name="search" value="Search">
            <input type="reset" name="clear" value="Clear">
        </form>
    </div>
    <table>
        <tr>
            <th>Profile Photo</th>
            <th>Name</th>
            <th>Details</th>
        </tr>
    <!-- facebook API-->
    <?php
        if ($_SERVER["REQUEST_METHOD"] == POST){
            $q = "q=";
            $type = "type=";
            $fields = "fields=id,name,picture.width(700).height(700)";
            $access_token = "access_token=EAACEdEose0cBAKMvMXFOL5b522rZB2Hmk6xZBr4yLslGOlZBktsZANvZAT6WJBid22XacelhmW7dnXj0ieTAm2bBeZBDMYBO8SGjAtqqsow1vaR4DKQEMVWYEm1p6YyNxhzynjq9JhjdwBx1K6XRbZCkXRyNTnt8NMkKyd7G8tKZBMrKYLwzIZBNpBCo5oLVC47gZD";
            $url= "https://graph.facebook.com/v2.8/search?";
            $url .= $q . $_POST["keyword"] . "&" . $type . $_POST[searchType] . "&" . $fields . "&" . $access_token;

            $response = file_get_contents($url);
            $jsonData = json_decode($response);
            //echo $jsonData -> data[0] -> id;
            //echo $jsonData -> data[0] -> name;
            //echo $jsonData -> data[0] -> picture -> width;
            //echo $jsonData -> data[0] -> picture -> height;
            //echo $jsonData -> data[0] -> picture -> is_silhouette;
            //echo $jsonData -> data[0] -> picture -> url;
            foreach($jsonData -> data as $each){
                echo "<tr>";
                echo "<td>" . "<img src='" . $each -> picture -> data -> url . "' height=30px width=40px>" . "</td>";
                echo "<td>" . $each -> name . "</td>";
                echo "<td>" . "<a href='https://facebook.com'>Details</a>" . "</td>";
                echo "</tr>";
            }
        }
    ?>
    </table>

    <div class="bottom">result</div>

</body>
</html>