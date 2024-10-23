$(document).ready(function() {
    // Initialize the price range slider
    $(".price-range").slider({
        range: true,
        min: 0,
        max: 100,
        values: [0, 100],
        slide: function(event, ui) {
            $("#minamount").val(ui.values[0] + " TND");
            $("#maxamount").val(ui.values[1] + " TND");
        }
    });

    $("#minamount").val($(".price-range").slider("values", 0) + " TND");
    $("#maxamount").val($(".price-range").slider("values", 1) + " TND");

    // Filter function
    $("#filterButton").click(function(e) {
        e.preventDefault();
        var minPrice = $(".price-range").slider("values", 0);
        var maxPrice = $(".price-range").slider("values", 1);

        $(".product__item").each(function() {
            var price = parseFloat($(this).find(".product__price").text());
            if (price >= minPrice && price <= maxPrice) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});