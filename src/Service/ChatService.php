<?php
// src/Service/ChatService.php
namespace App\Service;

class ChatService
{
    public function ordenarId(string $id1, string $id2)
    {
        if ((int) $id1 > (int) $id2) {
            return $id2 . "" . $id1;
        } else {
            return $id1 . "" . $id2;
        }
    }
}
