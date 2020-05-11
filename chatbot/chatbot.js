/**
 * Created by aravind on 4/28/17.
 */


$(function () {
    window.initialMessageDisplayed = false;
    $(document).mouseenter(function () {
        if (!window.initialMessageDisplayed) {
            var obj = JSON.parse($("#dom-target").text());
            var event = obj.result.action;
            var newMessage = obj.result.fulfillment.speech.linkify();
            var messagesContainer = $('.messages');
            messagesContainer.append([
                '<li class="other">',
                newMessage,
                '</li>'
            ].join(''));
			openElement();
            window.initialMessageDisplayed = true;
        }
    });
});

function getBotMessage(query) {
    var guid = ($("#sessionId").text()).trim();
    $.ajax({
        type: 'post',
        url: 'process.php',
        data: {submit: true, message: query, sessionid: guid},
        success: function (response) {
            var obj = JSON.parse(response);
            var event = obj.result.action;
            var messagesContainer = $('.messages');
            messagesContainer.append([
                '<li class="other">',
                obj.result.fulfillment.speech.linkify(),
                '</li>'
            ].join(''));
        }
    });
}
if (!String.linkify) {
    String.prototype.linkify = function () {

        // http://, https://, ftp://
        var urlPattern = /\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/gim;

        // www. sans http:// or https://
        var pseudoUrlPattern = /(^|[^\/])(www\.[\S]+(\b|$))/gim;

        // Email addresses
        var emailAddressPattern = /[\w.]+@[a-zA-Z_-]+?(?:\.[a-zA-Z]{2,6})+/gim;

        return this
                .replace(urlPattern,
                        '<a class="answerLink" style="color:#0000EE" target="_blank" href="$&">$&</a>')
                .replace(pseudoUrlPattern,
                        '$1<a class="answerLink" style="color:#0000EE" target="_blank" href="http://$2">$2</a>')
                .replace(emailAddressPattern,
                        '<a class="answerLink" style="color:#0000EE" href="mailto:$&">$&</a>');
    };
}