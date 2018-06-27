<?php
    include("config.php");
    include("classes/SiteResultsProvider.php");
    include("classes/ImageResultsProvider.php");

    if(!isset($_GET["term"]) || $_GET["term"] == ""){
        header('Location: index.php');
        exit;
    } else {
        $term = $_GET["term"];
        // echo "No search term";
    }

    $type = isset($_GET["type"]) ? $_GET["type"] : "sites";
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Results - Search Engine</title>
    <!-- css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.4.0.min.js"
        integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
    
    <!-- scripts -->
    <!-- for hiding browser tooltip for invalid input -->
    <script src="assets/js/tooltiphider.js"></script>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="headerContent">
                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets/images/search_logo.svg" alt="">
                    </a>
                </div>
                <div class="searchContainer">
                    <form action="search.php" method="GET">
                        <div class="searchBarContainer">
                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <input type="text" class="searchBox" name="term" value="<?php echo $term; ?>" required>
                            <button class="searchButton">
                                <img src="assets/images/icons/searching_icon.svg" alt="">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tabsContainer">
                <ul class="tabList">
                    <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>Sites</a>
                    </li>
                    <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=images"; ?>'>Images</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mainResultsSection">
            <?php

                if ($type == "sites") {
                    $resultsProvider = new SiteResultsProvider($con);
                    $pageSize = 20;
                }
                else {
                    $resultsProvider = new ImageResultsProvider($con);
                    $pageSize = 30;
                }

                $numResults = $resultsProvider->getNumResults($term);

                echo "<p class='resultsCount'>$numResults results found</p>";

                echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
            ?>
        </div>

        <div class="paginationContainer">
            <div class="pageButtons">
                <div class="pageNumberContainer">
                    <img src="assets/images/pageStart.svg" alt="">
                </div>

                <?php

                    $pagesToShow = 10;
                    $numPages = ceil($numResults / $pageSize);
                    $pagesLeft = min($pagesToShow, $numPages);

                    $currentPage = $page - floor($pagesToShow / 2);

                    if ($currentPage < 1) {
                        $currentPage = 1;
                    }

                    if ($currentPage + $pagesLeft > $numPages + 1) {
                        $currentPage = $numPages + 1 - $pagesLeft;
                    }

                    while ($pagesLeft != 0 && $currentPage <= $numPages) {

                        if ($currentPage == $page) {
                            echo "<div class='pageNumberContainer'>
                                        <img src='assets/images/pageSelected.svg'>
                                        <span class='pageNumber'>$currentPage</span>
                                    </div>";
                        }
                        else {
                            echo "<div class='pageNumberContainer'>
                                        <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                            <img src='assets/images/page.svg'>
                                            <span class='pageNumber'>$currentPage</span>
                                        </a>
                                    </div>";
                        }

                        $currentPage++;
                        $pagesLeft--;
                    }
                ?>

                <div class="pageNumberContainer">
                    <img src="assets/images/pageEnd.svg" alt="">
                </div>
            </div>
        </div>

        <div class="footer resultspage">
            <div class="copyright">
                &copy; 2018 - A Personal Project <i>by</i> <a href="http://towsif.me" target="_blank">Towsif Ahmed Omi</a>
            </div>
        </div>
    </div>

    <!-- scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>