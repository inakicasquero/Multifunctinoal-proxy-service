<?php

declare(strict_types=1);

namespace App\Models;

final class Invoice extends Model
{
    protected $connection = 'default';
    protected $table = 'invoice';
}
