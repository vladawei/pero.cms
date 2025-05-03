<?php
echo '<pre>' . htmlspecialchars($this->data["error_msg"]['error_message'] ?? 'Unknown error') . '</pre>';
if (isset($this->data["error_msg"]['exception'])) {
    echo '<h3>Exception Details:</h3>';
    echo '<pre>';
    echo htmlspecialchars((string) $this->data["error_msg"]['exception']);
    echo '</pre>';
}
?>
Выведи этот текст