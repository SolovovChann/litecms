let closeButtons = document.querySelectorAll(".btn.btn-close")
for (button of closeButtons) {
    button.onclick = (e) => {
        e.currentTarget.parentNode.classList.add('hidden')
    }
}