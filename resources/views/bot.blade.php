<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="center-text">
        <h2>ChatGPT ChatBox</h2>
        <p>Model: text-curie-001</p>
        <p>Max Token: 100</p>
    </div>
    <div id="body">

        <div id="chat-circle" class="btn btn-raised">
            <div id="chat-overlay"></div>
            {{-- <i class="material-icons">speaker_phone</i> --}}
            <img src="https://img.icons8.com/dotty/80/null/communication.png" height="50px"/>
        </div>

        <div class="chat-box">
            <div class="chat-box-header">
                ChatBot
                <span class="chat-box-toggle"><i class="material-icons">close</i></span>
            </div>
            <div class="chat-box-body">
                <div class="chat-box-overlay">
                </div>
                <div class="chat-logs">

                </div>
                <!--chat-log -->
            </div>
            <div class="chat-input">
                <form>
                    <input type="text" id="chat-input" placeholder="Send a message..." />
                    <button type="submit" class="chat-submit" id="chat-submit"><i class="material-icons">send</i></button>
                </form>
                <button id="voice-button" class="chat-submit" disabled><i class="material-icons">mic</i></button>
            </div>
        </div>




    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web-speech-api/0.1.0/speech-recognition.js"></script>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).ready(function() {
            var INDEX = 0;
            $("#chat-submit").click(function(e) {
                e.preventDefault();
                var message = $("#chat-input").val();
                if (message.trim() == '') {
                    return false;
                }

                // toastr.success(message);

                $.ajax({
                    url: '{{ route('bot.send') }}',
                    type: 'POST',
                    data: { message: message },
                    success: function(response) {
                        generate_message(message, 'self');
                        setTimeout(function() {
                            generate_message(response.message, 'user');
                        }, 1000)
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log("Status: "+xhr.status+ " Message: "+thrownError);
                    }
                });

                // generate_message(message, 'self');
                // var buttons = [{
                //         name: 'Existing User',
                //         value: 'existing'
                //     },
                //     {
                //         name: 'New User',
                //         value: 'new'
                //     }
                // ];
                // setTimeout(function() {
                //     generate_message(message, 'user');
                // }, 1000)

            })

            function generate_message(message, type) {
                INDEX++;
                var str = "";
                str += "<div id='cm-msg-" + INDEX + "' class='chat-msg " + type + "'>";
                str += "          <span class='msg-avatar'>";
                str +=
                    "            <img src='https://img.icons8.com/color/48/null/bot.png'>";
                str += "          </span>";
                str += "          <div class='cm-msg-text'>";
                str += message;
                str += "          </div>";
                str += "        </div>";
                $(".chat-logs").append(str);
                $("#cm-msg-" + INDEX).hide().fadeIn(300);
                if (type == 'self') {
                    $("#chat-input").val('');
                }
                $(".chat-logs").stop().animate({
                    scrollTop: $(".chat-logs")[0].scrollHeight
                }, 1000);
            }

            // function generate_button_message(message, buttons) {
            //     /* Buttons should be object array
            //       [
            //         {
            //           name: 'Existing User',
            //           value: 'existing'
            //         },
            //         {
            //           name: 'New User',
            //           value: 'new'
            //         }
            //       ]
            //     */
            //     INDEX++;
            //     var btn_obj = buttons.map(function(button) {
            //         return "              <li class='button'><a href='javascript:;' class='btn btn-primary chat-btn' chat-value='" +
            //             button.value + "'>" + button.name + "</a></li>";
            //     }).join('');
            //     var str = "";
            //     str += "<div id='cm-msg-" + INDEX + "' class='chat-msg user'>";
            //     str += "          <span class='msg-avatar'>";
            //     str +=
            //         "            <img src='https://img.icons8.com/color/48/null/user.png'>";
            //     str += "          </span>";
            //     str += "          <div class='cm-msg-text'>";
            //     str += message;
            //     str += "          </div>";
            //     str += "          <div class='cm-msg-button'>";
            //     str += "            <ul>";
            //     str += btn_obj;
            //     str += "            </ul>";
            //     str += "          </div>";
            //     str += "        </div>";
            //     $(".chat-logs").append(str);
            //     $("#cm-msg-" + INDEX).hide().fadeIn(300);
            //     $(".chat-logs").stop().animate({
            //         scrollTop: $(".chat-logs")[0].scrollHeight
            //     }, 1000);
            //     $("#chat-input").attr("disabled", true);
            // }

            // $(document).delegate(".chat-btn", "click", function() {
            //     var value = $(this).attr("chat-value");
            //     var name = $(this).html();
            //     $("#chat-input").attr("disabled", false);
            //     generate_message(name, 'self');
            // })

            const chatInputArea = document.getElementById('chat-input');
            const voiceButton = document.getElementById('voice-button');
            let recognition;

            // Initialize SpeechRecognition API
            if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {

                recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                // recognition.continuous = true;
                // recognition.interimResults = false;
                recognition.lang = '*'; // *; // Set the language for speech recognition

                recognition.onstart = function() {
                    console.log('Speech recognition started');
                };

                recognition.onresult = function(event) {
                    // const transcript = event.results[event.results.length - 1][0].transcript;
                    const transcript = event.results[0][0].transcript;
                    console.log('Recognized speech:', transcript);
                    chatInputArea.value = transcript;
                };

                // Start speech recognition when the voice button is clicked
                voiceButton.addEventListener('click', function() {
                    recognition.start();
                });

                recognition.onerror = function(event) {
                    console.error('Speech recognition error:', event.error);
                };

                recognition.onend = function() {
                    console.log('Speech recognition ended');
                };

                voiceButton.disabled = false;
            } else {
                voiceButton.disabled = true;
                console.error('Speech recognition not supported');
            }


            $("#chat-circle").click(function() {
                $("#chat-circle").toggle('scale');
                $(".chat-box").toggle('scale');
            })

            $(".chat-box-toggle").click(function() {
                $("#chat-circle").toggle('scale');
                $(".chat-box").toggle('scale');
            })

        })
    </script>

</body>

</html>
