document.getElementById("addNoteForm").onsubmit = function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch("add_note.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // Reload to display the new note
    })
    .catch(err => console.error(err));
};
