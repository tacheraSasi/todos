document.addEventListener('DOMContentLoaded', () => {
    console.log(window.location.href)
    const modal = document.querySelector('.modal');
    const addTaskBtn = document.querySelector('.add-task-btn');
    const closeModalBtn = document.querySelector('.modal-close');

    let fromURL = document.getElementById("from_url")
    fromURL.value = window.location.href

    // Showing modal when Add Task button is clicked
    addTaskBtn.addEventListener('click', () => {
        modal.classList.add('show');
    });

    // Hiding modal when close button is clicked
    closeModalBtn.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
});
