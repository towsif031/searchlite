<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Engine</title>
    <!-- css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="wrapper indexPage">
        <div class="mainSection">
            <div class="logoContainer">
                <img src="assets/images/search_logo.svg" alt="">
            </div>
            <div class="searchContainer">
                <form action="search.php" method="GET">
                    <input type="text" class="searchBox" name="term">
                    <input type="submit" class="searchButton" value="Search">
                </form>
            </div>
        </div>
    </div>
</body>

</html>