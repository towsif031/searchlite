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
});

function increaseLinkClicks(linkId, url) {

}