// a script who show a popup and let the user answer to a specific message
function answer_mails(recipient, title)
{
    var answer = prompt("Reply to " + recipient, "Write your message here");

    if (answer != null)
    {
        post('/send_message.php', {recipient: recipient, title: title, message: answer});
    }
}

// http://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
function post(path, params, method)
{
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}
