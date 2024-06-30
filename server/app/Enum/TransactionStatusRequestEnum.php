<?php
namespace App\Enum;

enum TransactionStatusRequestEnum:string
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const EXPIRED = 'expired';
}