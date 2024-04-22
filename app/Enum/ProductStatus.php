<?php

namespace App\Enum;

enum ProductStatus : string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
}
