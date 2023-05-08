<!DOCTYPE html>
<html>

<head>
    <title>Chatbox</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        #chatbox-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 320px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
        }

        #chatbox-header {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        #chatbox-header h3 {
            margin: 0;
            font-size: 16px;
        }

        #chatbox-messages {
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
        }

        #chatbox-input {
            display: flex;
            align-items: center;
            padding: 10px;
            border-top: 1px solid #ccc;
        }

        #user-input {
            flex-grow: 1;
            padding: 8px;
            border: none;
            border-radius: 5px;
            outline: none;
        }

        #send-button {
            padding: 8px 12px;
            margin-left: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Style the chat messages */
        .chat-message {
            margin-bottom: 10px;
            padding: 6px 10px;
            border-radius: 5px;
        }

        .user-message {
            text-align: right;
            background-color: #d1f1d1;
        }

        .bot-message {
            text-align: left;
            background-color: #f1f1d1;
        }

        .message-content {
            display: inline-block;
            max-width: 80%;
        }

        .message-icon {
            display: inline-block;
            width: 30px;
            height: 30px;
            /* background-image: url('path_to_icon_image');
            background-size: cover; */
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-icon {
            background-image: url('https://img.icons8.com/color/48/null/user.png');
            background-size: cover;
            float: right;
        }

        .bot-icon {
            background-image: url('https://img.icons8.com/color/48/null/bot.png');
            background-size: cover;
        }

        .message-time {
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div id="chatbox-container">
        <div id="chatbox-header">
            <h3>Chat</h3>
        </div>
        <div id="chatbox-messages">
            <!-- Messages will be dynamically added here -->
            <div class="message-pair-box">
                <div class="chat-message user-message">
                    <div class="message-content">
                        <div class="message-icon user-icon"></div>
                        Hello
                    </div>
                    <div class="message-time">11:30 AM</div>
                </div>
                <div class="chat-message bot-message">
                    <div class="message-content">
                        <div class="message-icon bot-icon"></div>
                        Hi
                    </div>
                    <div class="message-time">11:31 AM</div>
                </div>
            </div>
        </div>
        <div id="chatbox-input">
            <input type="text" id="user-input" placeholder="Type your message..." />
            <button id="send-button">Send</button>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('message-form');
            var input = document.getElementById('user-message');
            var messagesContainer = document.getElementById('chat-messages');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var message = input.value;

                // Display user message
                displayMessage('You', message);

                // Send user message to the server and get response
                sendMessageToServer(message)
                    .then(function(response) {
                        var botReply = response.message;

                        // Display bot reply
                        displayMessage('Bot', botReply);
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                    });

                // Clear input field
                input.value = '';
            });

            function displayMessage(sender, text) {
                var messageElement = document.createElement('div');
                messageElement.className = 'message';
                messageElement.innerHTML = '<strong>' + sender + ':</strong> ' + text;

                messagesContainer.appendChild(messageElement);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function sendMessageToServer(message) {
                return fetch('/chatbot', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    });
            }
        });
    </script>
</body>

</html>
