$('.pagination').each(
    function() {
        var maxWidth = 0;

        $(this).find('.page').each(
            function() {
                maxWidth = Math.max(maxWidth, $(this).width());
            }
        ).width(maxWidth);
    }
);