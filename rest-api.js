jQuery(document).ready(function($) {

    $('#submit').click(function(){        

        $.ajax({
            url: "http://localhost/wp_testimoni/sites1/wp-json/wp/v2/posts",
            type: "POST",
            headers: {
                "X-WP-Nonce": wpApiSettings.nonce,
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                "title": $('#title').val() ,
                "content": $('#content').val(),
                "status": $('#status').val()
            },
            success: function(response) {
                console.log(response);
            },
            error: function(response) {
                alert(response);
            }

        });
        // console.log("hello");
    });
    

});