function noStyle(input_event) {
    if (document.getElementById(input_event).hasAttribute("style")) {
        document.getElementById(input_event).removeAttribute("style");
    }
}