// prznoszenie na odpowiednią scieżkę po wybraniu selectora

document.getElementById('taskForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var selectedTask = document.getElementById('taskSelect').value;
    if (selectedTask) {
        var newUrl = '/' + selectedTask;
        console.log(newUrl);
        window.location.href = newUrl; 
    }
});