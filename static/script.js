function referencePost(post) {
    const textareas = document.getElementsByName("message");
    const textarea = textareas[1];

    textarea.value += ">>" + post + "\n";
    textarea.focus();
}

function copyThreadId(id) {
    navigator.clipboard.writeText("@" + id)
	.then(() => {
	    console.log('Thread ID copied to clipboard');
	})
	.catch(err => {
	    console.error('Error copying thread ID: ', err);
	});
}

function copyThreadUrl(id) {
    const baseUrl = window.location.protocol + "//" + window.location.host;
    const threadUrl = baseUrl + "/thread.php?id=" + id;

    // Copy the URL to the clipboard
    navigator.clipboard.writeText(threadUrl)
	.then(() => {
	    console.log('URL copied to clipboard');
	})
	.catch(err => {
	    console.error('Failed to copy URL: ', err);
	});
}
