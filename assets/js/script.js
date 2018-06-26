$(document).ready(function() {

    $(".result").on("click", function() {

        var id = $(this).attr("data-linkId");
        var url = $(this).attr("href");

        // if (!id) {
        //     alert("data-linkId attribute not found!");
        // }

        increaseLinkClicks(id, url);

        return false;
    });

    var grid = $(".imageResults");

    grid.masonry({
        itemSelector: ".gridItem",
        columnWidth: 200,
        gutter: 5,
        transitionDuration: '0.8s',
        isInitLayout: false
    });

});

function loadImage(src, className) {

    var image = $("<img>");

    image.on("load", function() {
        $("." + className + " a").append(image);
    });

    image.on("error", function() {

    });

    image.attr("src", src);

}

function increaseLinkClicks(linkId, url) {

    $.post("ajax/updateLinkCount.php", {linkId: linkId}).done(function(result) {
        if (result != "") {
            alert(result);
            return;
        }

        window.location.href = url;
    });
}