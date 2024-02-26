<?php

namespace Larabookir\Gateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayConfig extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('gateway.gateway_configs_table_name', 'gateway_configs');
    }
}
