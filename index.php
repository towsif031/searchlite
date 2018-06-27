<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Engine</title>
    <!-- css -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- scripts -->
    <!-- for hiding browser tooltip for invalid input -->
    <script src="assets/js/tooltiphider.js"></script>
</head>

<body>
    <div class="wrapper indexPage">
        <div class="mainSection">
            <div class="logoContainer">
                <img src="assets/images/search_logo.svg" alt="">
            </div>
            <div class="searchContainer">
                <form action="search.php" method="GET">
                    <input type="text" class="searchBox" name="term" autofocus required oninvalid="this.setCustomValidity('Please Enter valid email')" oninput="setCustomValidity('')">
                    <input type="submit" class="searchButton" value="Search">
                </form>
            </div>
        </div>

        <div class="footer">
            <div class="copyright">
                &copy; 2018 - A Personal Project <i>by</i> <a href="http://towsif.me" target="_blank">Towsif Ahmed Omi</a>
            </div>
        </div>
    </div>
</body>

</html>