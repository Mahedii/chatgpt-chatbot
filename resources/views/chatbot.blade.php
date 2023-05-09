<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css" />
    <style>
        #chatbot {
            background: #444654;
            color: white;
        }
        .bot-message {
            background: #0f0000;
            color: rgb(255, 0, 0);
            padding: 10px;
        }
        .user-message {
            padding: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2 mt-5">
            <div class="card">
                <div class="card-header">
                    Chatbot
                </div>
                <div class="card-body">
                    <div id="chatbot">
                        <div id="chat-area"></div>
                        <form >
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" id="message" value="What is openai?" placeholder="Type your message here..." autocomplete="off">
                                <button type="submit" class="btn btn-primary" id="send-message">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function() {

        $('#chatbot form').submit(function(event) {
            event.preventDefault();
            var message = $('#message').val();
            if (message.trim() === '') {
                return;
            }
            $('#message').val('');

            $.ajax({
                url: '{{ route('chatbot.send') }}',
                type: 'POST',
                data: { message: message },
                success: function(response) {

                    $('#chat-area').append('<div class="user-message">' + message + '</div>');
                    $('#chat-area').append('<div class="bot-message">' + response.message + '</div>');
                    $('#message').val('');

                }
            });
        });

    });

</script>

</body>
</html>
