<?php

class Helpers
{
    public static function escapeHTML($data)
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
?>