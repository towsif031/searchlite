$(document).ready(function() {

    $(".result").on("click", function() {

        var url = $(this).attr("href");
        console.log(url);

        return false;
    });
});

function increaseLinkClicks(linkId, url) {

}