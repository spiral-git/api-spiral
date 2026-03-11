<?php

namespace App\Domain\Entity;

class RespuestaEntity
{
    
    public string $Message;
    public bool $IsSuccess;
    public $Data;

    public function __construct(string $message, bool $isSuccess, $data)
    {
        $this->Message = $message;
        $this->IsSuccess = $isSuccess;
        $this->Data = $data;
    }
}
