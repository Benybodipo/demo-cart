$(function (){

    $('.btn-add-to-cart').click(function (e) {
        e.stopImmediatePropagation();

        var url = $(this).data('route');
        // return console.log(url);

        $.ajax({
            url: url,
            method: 'POST',
            data: {'_token': $('meta[name="csrf_token"]').attr('content')},
            success: function (response) {
                console.log(response);
            }
        })
    });

    $('.update-quantity').change(function (e) {
        e.stopImmediatePropagation();

        var $this = $(this); 
        var url = $(this).data('route');
        var qty = $(this).val();
        // return console.log(url);
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                '_token': $('meta[name="csrf_token"]').attr('content'),
                'qty': qty
            },
            success: function (response) {
                var total = 0;
                var count = 0;
                
                $this.parent().siblings('.subtotal')
                        .find('span').text((parseFloat(response['sub-total'], 2)).toFixed(2));

                $.each($("#cart-table .subtotal span"), function (key, val){
                    var n = parseFloat($(this).text(),3)
                    total += n;
                });

                $.each($("#cart-table td input[type='number']"), function (key, val){
                    var n = parseInt($(this).val());
                    count += n;
                });
                
                
                $("#cart-table .count").text(count);
                $("#cart-table .total").text(total.toFixed(2));
                
            }
        })
    });

    $('.delete-item').click(function (e) {
        e.stopImmediatePropagation();

        var $this = $(this); 
        var url = $(this).data('route');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {'_token': $('meta[name="csrf_token"]').attr('content')},
            success: function (response) {
                
                $this.parent().parent().remove();
                
                if (response.count <= 0)
                {
                    $('.cart-row .cart-container').remove();
                    $('.cart-row').append('<h3 class="text-center">Nothing in your cart!</h3>');
                }
                console.log(response);
            }
        })
    });
});