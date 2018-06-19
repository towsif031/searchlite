<?php
    ini_set('max_execution_time', 0); // for infinite time of execution 

    include("config.php");
    include("classes/DomDocumentParser.php");

    $alreadyCrawled = array();
    $crawling = array();
    $alreadyFoundImages = array();

    function linkExists($url) {
        global $con;

        $query = $con->prepare("SELECT * FROM sites WHERE url = :url");

        $query->bindParam(":url", $url);

        $query->execute();

        return $query->rowCount() != 0;
    }

    function insertLink($url, $title, $description, $keywords) {
        global $con;

        $query = $con->prepare("INSERT INTO sites(url, title, description, keywords) 
                                Values(:url, :title, :description, :keywords)");

        $query->bindParam(":url", $url);
        $query->bindParam(":title", $title);
        $query->bindParam(":description", $description);
        $query->bindParam(":keywords", $keywords);

        return $query->execute();
    }
    
    function insertImage($url, $src, $alt, $title) {
        global $con;

        $query = $con->prepare("INSERT INTO images(siteUrl, imageUrl, alt, title) 
                                Values(:siteUrl, :imageUrl, :alt, :title)");

        $query->bindParam(":siteUrl", $url);
        $query->bindParam(":imageUrl", $src);
        $query->bindParam(":alt", $alt);
        $query->bindParam(":title", $title);

        $query->execute();
    }

    function createLink($src, $url) {
        
        $scheme = parse_url($url)["scheme"];    // http or https
        $host = parse_url($url)["host"];        // www.domain.com

        if (substr($src, 0, 2) == "//")  {                                          // "//www.domain.com"
            $src = $scheme . ":" . $src;
        }
        else if(substr($src, 0, 1) == "/")  {                                       // "/about/aboutUs.php"
            $src = $scheme . "://" . $host . $src;
        }
        else if(substr($src, 0, 2) == "./")  {                                      // "./about/aboutUs.php"
            $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);
        }
        else if(substr($src, 0, 3) == "../")  {                                     // "../about/aboutUs.php"
            $src = $scheme . "://" . $host . "/" . $src;
        }
        else if(substr($src, 0, 5) != "https" && substr($src, 0, 4) != "http") {    // "about/aboutUs.php"
            $src = $scheme . "://" . $host . "/" . $src;
        }

        return $src;

    }

    function getDetails($url) {

        global $alreadyFoundImages;

        $parser = new DomDocumentParser($url);

        $titleArray = $parser->getTitleTags();

        if (sizeof($titleArray) == 0 || $titleArray->item(0) == NULL) {
            return;
        }

        $title = $titleArray->item(0)->nodeValue;
        $title = str_replace("\n", "", $title);

        if ($title == "") {
            return;
        }

        $description = "";
        $keywords = "";

        $metasArray = $parser->getMetaTags();

        foreach ($metasArray as $meta) {
            
            if ($meta->getAttribute("name") == "description") {
                $description = $meta->getAttribute("content");
            }
            
            if ($meta->getAttribute("name") == "keywords") {
                $keywords = $meta->getAttribute("content");
            }
        }

        $description = str_replace("\n", "", $description);
        $keywords = str_replace("\n", "", $keywords);

        // echo "URL: $url, Title: $title, Description: $description, Keywords: $keywords <br>";

        if (linkExists($url)) {
            echo "$url already Exists. <br>";
        }
        else if(insertLink($url, $title, $description, $keywords)) {
            echo "SUCCESS: $url <br>";
        }
        else {
            echo "Failed to insert $url <br>";
        }
        
        $imageArray = $parser->getImages();

        foreach ($imageArray as $image) {
            $src = $image->getAttribute("src");
            $alt = $image->getAttribute("alt");
            $title = $image->getAttribute("title");
            
            if (!$title && !$alt) {
                continue;
            }

            $src = createLink($src, $url);

            if (!in_array($src, $alreadyFoundImages)) {
                $alreadyFoundImages[] = $src;

                insertImage($url, $src, $alt, $title);
            }
        }

    }

    function followLinks($url) {

        global $alreadyCrawled;
        global $crawling;

        $parser = new DomDocumentParser($url);

        $linkList = $parser->getLinks();

        foreach ($linkList as $link) {
            $href = $link->getAttribute("href");

            if (strpos($href, "#") !== false) {
                continue;
            } else if (substr($href, 0, 11) == "javascript:"){
                continue;
            }

            $href = createLink($href, $url);

            if (!in_array($href, $alreadyCrawled)) {
                $alreadyCrawled[] = $href;
                $crawling[] = $href;

                getDetails($href);
            }

        }

        array_shift($crawling);

        foreach ($crawling as $site) {
            followLinks($site);
        }
    }

    $startUrl = "http://www.bbc.com";

    followLinks($startUrl);
?>