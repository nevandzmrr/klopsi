<?php
function esc($value) {
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
