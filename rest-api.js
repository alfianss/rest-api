jQuery(document).ready(function($) {

    // POST CREATE & UPDATE
    $('#submit').click(function(){  
        var id = $(this).data('value');
        var url = '';
        if(!id) {
            url = "http://localhost/wp_testimoni/sites1/wp-json/wp/v2/posts"; 
        } else {            
            url = "http://localhost/wp_testimoni/sites1/wp-json/wp/v2/posts/"+id; 
        }

        $.ajax({
            url: url,
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
                refresh_list_post();

                $('#title').val();
                $('#content').val();
                $('#status').val();
            },
            error: function(response) {
                console.log(response);
            }

        });        
    });
    
    // DELETE POST
    $('button#btn-delete').click(function(){ 
        var id = $(this).data('value');
        
        $.ajax({
            url: "http://localhost/wp_testimoni/sites1/wp-json/wp/v2/posts/"+id,
            type: "DELETE",
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
                refresh_list_post();
            },
            error: function(response) {
                console.log(response);
            }
        });        
    });

    // EDIT POST
    $('button#btn-edit').click(function(){ 
        var id = $(this).data('value');
        
        $.ajax({
            url: "http://localhost/wp_testimoni/sites1/wp-json/wp/v2/posts/"+id,
            type: "GET",
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
                $('#title').val(response.title.rendered);
                $('#content').val(response.content.rendered);
                $('#status').val(response.status);
                $('button#submit').attr('data-value', response.id);
            },
            error: function(response) {
                console.log(response);
            }
        });        
    });

    function refresh_list_post() {
        $.ajax({
            url: "http://localhost/wp_testimoni/sites1/wp-json/wp/v2/posts/",
            type: "GET",
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
                
                var html = '';
                $.each(response, function(index, item){
                    html += '<tr><td>'+item.title.rendered+'</td><td>'+item.content.rendered+'</td><td>'+item.status+'</td>\
                             <td>\
                                <button data-value="'+response.id+'" id="btn-edit">Edit</button>&nbsp;\
                                <button data-value="'+response.id+'" id="btn-delete">Delete</button>\
                                </td></tr>';
                })
                $('#table-post').html(html);
            },
            error: function(response) {
                console.log(response);
            }
        });  
    }
    
    

});