document.getElementById('taskForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Zapobiega domyślnemu zachowaniu formularza

    var selectedTask = document.getElementById('taskSelect').value;
    if (selectedTask) {
        var newUrl = '/' + selectedTask; // Konstruuje nowy URL
        window.location.href = newUrl; // Przenosi użytkownika na nowy URL
    }
});