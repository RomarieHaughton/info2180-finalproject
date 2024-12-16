<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$contact_id = $_GET['id'] ?? null;

if (!$contact_id) {
    die("Contact ID is required.");
}

// Fetch contact details
$stmt = $pdo->prepare("SELECT c.*, CONCAT(u.firstname, ' ', u.lastname) AS assigned_to_name
                       FROM Contacts c 
                       LEFT JOIN Users u ON c.assigned_to = u.id
                       WHERE c.id = ?");
$stmt->execute([$contact_id]);
$contact = $stmt->fetch();

if (!$contact) {
    die("Contact not found.");
}

// Fetch notes for the contact
$note_stmt = $pdo->prepare("SELECT n.comment, n.created_at, CONCAT(u.firstname, ' ', u.lastname) AS created_by
                            FROM Notes n
                            JOIN Users u ON n.created_by = u.id
                            WHERE n.contact_id = ?");
$note_stmt->execute([$contact_id]);
$notes = $note_stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Contact</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Contact Details</h1>
    <p><strong>Title:</strong> <?= htmlspecialchars($contact['title']); ?></p>
    <p><strong>Full Name:</strong> <?= htmlspecialchars($contact['firstname'] . " " . $contact['lastname']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($contact['email']); ?></p>
    <p><strong>Company:</strong> <?= htmlspecialchars($contact['company']); ?></p>
    <p><strong>Telephone:</strong> <?= htmlspecialchars($contact['telephone']); ?></p>
    <p><strong>Type:</strong> <?= htmlspecialchars($contact['type']); ?></p>
    <p><strong>Assigned To:</strong> <?= htmlspecialchars($contact['assigned_to_name'] ?? 'Unassigned'); ?></p>
    <p><strong>Created At:</strong> <?= htmlspecialchars($contact['created_at']); ?></p>

    <form id="assignToMeForm">
        <button type="button" onclick="assignToMe()">Assign to Me</button>
        <button type="button" onclick="switchType()">Switch Type</button>
    </form>

    <h2>Notes</h2>
    <ul>
        <?php foreach ($notes as $note): ?>
            <li>
                <strong><?= htmlspecialchars($note['created_by']); ?>:</strong>
                <?= htmlspecialchars($note['comment']); ?>
                <em>(<?= htmlspecialchars($note['created_at']); ?>)</em>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Add a Note</h3>
    <form id="addNoteForm">
        <textarea name="comment" placeholder="Enter note" required></textarea><br>
        <input type="hidden" name="contact_id" value="<?= $contact_id; ?>">
        <button type="submit">Add Note</button>
    </form>

    <div id="noteResponse"></div>

    <script>
        // AJAX: Add Note
        document.getElementById("addNoteForm").onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("add_note.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("noteResponse").innerText = data;
                location.reload(); // Reload to show new note
            });
        };

        // AJAX: Assign to Me
        function assignToMe() {
            fetch(`update_contact.php?action=assign&contact_id=<?= $contact_id; ?>`, {
                method: "POST"
            }).then(response => response.text())
              .then(data => {
                  alert(data);
                  location.reload();
              });
        }

        // AJAX: Switch Type
        function switchType() {
            fetch(`update_contact.php?action=switch_type&contact_id=<?= $contact_id; ?>`, {
                method: "POST"
            }).then(response => response.text())
              .then(data => {
                  alert(data);
                  location.reload();
              });
        }
    </script>
</body>
</html>
