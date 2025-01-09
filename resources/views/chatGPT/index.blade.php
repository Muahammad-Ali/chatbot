<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT-like Interface</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styling the chat container */
        .chat-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            background-color: #f7f7f7;
            padding: 10px;
        }

        /* Chat box styles */
        .chat-box {
            height: 80vh;
            overflow-y: scroll;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        /* Chat message styling */
        .chat-box p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .chat-box .user-message {
            background-color: #007bff;
            color: #fff;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            max-width: 80%;
        }

        .chat-box .chatbot-message {
            background-color: #f1f1f1;
            color: #333;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            max-width: 80%;
        }

        /* Styling the input area */
        .chat-input-container {
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Input field styling */
        .chat-input-container input {
            border-radius: 20px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            width: 85%;
        }

        /* Send button styling */
        .chat-input-container button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .chat-input-container button:hover {
            background-color: #0056b3;
        }

        /* Scrollbar styling for the chat box */
        .chat-box::-webkit-scrollbar {
            width: 8px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background-color: #007bff;
            border-radius: 10px;
        }

        .chat-box::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="container chat-container">
        <div class="chat-box border">
            <!-- Sample messages -->
            <p class="user-message"><strong>User:</strong> Hello, Chatbot!</p><br>
            <p class="chatbot-message"><strong>Chatbot:</strong> Hello, User! How can I assist you today?</p><br>
        </div>
        <div class="chat-input-container">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Type your message here..." aria-label="Message input">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">Send</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            const $sendButton = $('.btn-primary');
            const $inputField = $('.form-control');
            const $chatBox = $('.chat-box');

            $sendButton.on('click', function() {
                const messageContent = $inputField.val();

                // If the message is empty, return
                if (!messageContent) return;

                // Append user message to chat box
                $chatBox.append(`<p class="user-message"><strong>User:</strong> ${messageContent}</p>`);

                // Send the content to the Laravel route
                $.ajax({
                    url: '{{ route('get_chat') }}',
                    method: 'POST',
                    data: {
                        message: messageContent
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Append the chatbot's response to the chat box
                        if (data && data.content) {
                            $chatBox.append(`<p class="chatbot-message"><strong>Chatbot:</strong> ${data.content}</p>`);

                            // Scroll to the bottom of the chat box to show the latest messages
                            $chatBox.scrollTop($chatBox[0].scrollHeight);
                        }

                        // Clear the input field
                        $inputField.val('');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>
