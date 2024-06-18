<?php

namespace App\Enums;

enum LedgerType: string
{
    case EXPENSE = 'expense';
    case INCOME = 'income';
}