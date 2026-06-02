document.querySelectorAll('textarea').forEach(textarea => {
    textarea.style.height = textarea.scrollHeight + 'px';
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});