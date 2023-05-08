<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    </head>
    <body class="antialiased">

        <form id="chat-form">
            <input type="text" id="message" name="message">
            <button type="submit">Send</button>
        </form>

        <div id="chat-log"></div>

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#chat-form').on('submit', function(event) {
                event.preventDefault();
                var message = $('#message').val();
                $.ajax({
                    url: '/chatbot',
                    type: 'POST',
                    data: {message: message},
                    dataType: 'json',
                    success: function(response) {
                    var text = response.message;
                    $('#chat-log').append('<div>' + text + '</div>');
                    },
                    error: function(response) {
                    console.log(response);
                    }
                });
                $('#message').val('');
            });

        </script>

    </body>
</html>
