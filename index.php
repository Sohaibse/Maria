<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="ChatBot.css">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="js/chatbot.js"></script>
    </head>
    <body>
        <?php
        $sessionID = uniqid('', true);
        include('starter.php');
        $sessionID = uniqid('', true);
        ?>
        <div class="floating-chat">
            <i class="fa fa-comments" aria-hidden="true"></i>
            <div class="chat">
                <div class="header">
                    <span class="title" style="font-size:15px">
                       Dialogflow ChatBot 
                    </span>
                    <button>
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <span style="display: none;" id="sessionId">
                    <?php
                    echo $sessionID;
                    ?>
                </span>
                <ul class="messages">
                </ul>
                <div class="footer">
                    <div class="text-box" contenteditable="true" placeholder="Lets Start Chat" id="message" name="date" value="" ></div>
                    <button id="sendMessage">send</button>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var element = $('.floating-chat');
            var myStorage = localStorage;

            if (!myStorage.getItem('chatID')) {
                myStorage.setItem('chatID', createUUID());
            }

            setTimeout(function () {
                element.addClass('enter');
            }, 1000);

			
            element.click(openElement);

            function openElement() {
                var messages = element.find('.messages');
                var textInput = element.find('.text-box');
                element.find('>i').hide();
                element.addClass('expand');
                element.find('.chat').addClass('enter');
                var strLength = textInput.val().length * 2;
                textInput.keydown(onMetaAndEnter).prop("diabled", false).focus();
                element.off('click', openElement);
                element.find('.header button').click(closeElement);
                element.find('#sendMessage').click(sendNewMessage);
                messages.scrollTop(messages.prop("scrollHeight"));
            }

            function closeElement() {
                element.find('.chat').removeClass('enter').hide();
                element.find('>i').show();
                element.removeClass('expand');
                element.find('.header button').off('click', closeElement);
                element.find('#sendMessage').off('click', sendNewMessage);
                element.find('.text-box').off('keydown', onMetaAndEnter).prop("diabled", true).blur();
                setTimeout(function ( ){
                    element.find('.chat').removeClass('enter').show()
                    element.click(openElement);
                } ,500);
            }

            function createUUID() {
                var s = [];
                var hexDigits = "0123456789abcdef";
                for (var i = 0; i < 36; i++) {
                    s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
                }
                s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
                s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
                s[8] = s[13] = s[18] = s[23] = "-";

                var uuid = s.join("");
                return uuid;
            }

            function sendNewMessage() {
                var userInput = $('.sendMessage');
                var newMessage = userInput.html().replace(/\<div\>|\<br.*?\>/ig, '\n').replace(/\<\/div\>/g, '').trim().replace(/\n/g, '<br>');

                if (!newMessage)
                    return;

                var messagesContainer = $('.messages');

                messagesContainer.append([
                    '<li class="self">',
                    newMessage,
                    '</li>'
                ].join(''));

                // clean out old message
                userInput.html('');
                // focus on input
                userInput.focus();
                getBotMessage(newMessage);
                messagesContainer.finish().animate({
                    scrollTop: messagesContainer.prop("scrollHeight")
                }, 250);
            }

            function onMetaAndEnter(event) {
                if ((event.metaKey || event.ctrlKey) && event.keyCode == 13) {
                    sendNewMessage();
                }
            }	
			function myFunction(btnVal) {
				$('.sendMessage').text(btnVal);
			}

        </script>
    </body>
</html>