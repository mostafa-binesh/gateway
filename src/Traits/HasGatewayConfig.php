<?php

namespace Larabookir\Gateway\Traits;

use Larabookir\Gateway\Models\GatewayConfig;

/**
 *  @Use this in your User model
 */
trait HasGatewayConfig
{
    /**
     * gatewayConfig relationship
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gatewayConfig()
    {
        return $this->belongsTo(GatewayConfig::class);
    }
}
