var maxWidth = 0;

$('#content .item').each(
    function() {
        maxWidth = Math.max(maxWidth, $(this).width());
    }
).width(maxWidth);