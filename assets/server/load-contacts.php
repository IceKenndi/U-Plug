<?php 
session_start();
require __DIR__ . "/../config/dbconfig.php";

$active_user = $_SESSION['user_id'];
$users = [];
$seen = [];

echo '<div class="contact-list-header">Contacts</div>';

$contactSql = "SELECT u.id, u.full_name, u.role, m.content, m.sent_at, m.sender_id
               FROM (SELECT student_id AS id, full_name, 'Student' AS role FROM student_users
                     UNION
                     SELECT faculty_id AS id, full_name, 'Faculty' AS role FROM faculty_users)
               AS u
               LEFT JOIN (SELECT sender_id, receiver_id, content, sent_at
                          FROM messages
                          WHERE sender_id = ? OR receiver_id = ?
                          ORDER BY sent_at DESC)
               AS m ON (m.sender_id = u.id AND m.receiver_id = ?) OR (m.receiver_id = u.id AND m.sender_id = ?)
               WHERE u.id != ?
               ORDER BY m.sent_at DESC";

$stmt = $conn->prepare($contactSql);
$stmt->bind_param("sssss", $active_user, $active_user, $active_user, $active_user, $active_user);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()){
  if (in_array($row['id'], $seen)) continue;
  $seen[] = $row['id'];
    
  if (isset($row['sender_id']) && $row['sender_id'] === $active_user) {
    $preview = 'You: ' . $row['content'];
  } else if (isset($row['sender_id'])) {
    $firstName = explode(" ", $row['full_name'])[0];
    $preview = $firstName . ": " . $row['content'];
  } else {
    $preview = 'No message yet';
  }
  echo '<div class="contact-button" data-id="' . $row['id'] . '">
          <button type="button">
            <div class="avatar-circle">' . strtoupper(substr($row['full_name'], 0, 1)) . '</div>
            <div class="contact-info">
              <div class="contact-name">' . htmlspecialchars($row['full_name']) . ' - (' . htmlspecialchars($row['role']) . ')</div>
              <div class="last-message">' . htmlspecialchars($preview) . '</div>
            </div>
          </button>
        </div>';

}
$stmt->close();

?>