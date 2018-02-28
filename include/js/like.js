var btn = $("button#like");

btn.each(function(i,e) {
    $(e).click(function(){
        var id = this.dataset.id;
        $.ajax({
            type: "GET",
            url: "like.php?pid=" + id,
            dataType: "json",
            success: function(data) {
                if (data.status == true) {
                    $('#like[data-id="'+ data.id + '"]').html(
                        '<i class="thumbs up icon"></i> ' + data.likes
                    );
                } else if (data.status == false) {
                    $('#like[data-id="'+ data.id + '"]').html(
                        '<i class="thumbs outline up icon"></i> ' + data.likes
                    );
                } else {
                    $('#like[data-id="'+ id + '"]').html(
                        '<i class="thumbs outline up icon"></i> ' + "--"
                    );
                }
            },
            error: function(jqXHR) {
                $('#like[data-id="'+ id + '"]').html(
                    '<i class="thumbs outline up icon"></i> ' + "--"
                );
            }
        });
    });
});