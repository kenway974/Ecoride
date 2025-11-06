<?php

namespace App\Enum;

enum EnergyType: string
{
    case GASOLINE = 'gasoline';
    case DIESEL = 'diesel';
    case HYBRID = 'hybrid';
    case ELECTRIC = 'electric';
    case OTHER = 'other';
}
