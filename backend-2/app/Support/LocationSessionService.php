<?php

namespace App\Support;

use App\Models\Item;
use Carbon\Carbon;

class LocationSessionService
{
    public function getExpiryMinutes(): int
    {
        return max((int) config('jwt.ttl', 60), 1);
    }

    public function getExpiryCutoff(): Carbon
    {
        return Carbon::now()->subMinutes($this->getExpiryMinutes());
    }

    public function isExpired(Item $lokasi): bool
    {
        if ((int) $lokasi->ISITEAMITEM_IS_OPEN !== 0) {
            return false;
        }

        if (empty($lokasi->DATE_OPEN)) {
            return false;
        }

        try {
            return Carbon::parse($lokasi->DATE_OPEN)->lte($this->getExpiryCutoff());
        } catch (\Throwable $error) {
            return false;
        }
    }

    public function close(Item $lokasi): bool
    {
        $lokasi->ISITEAMITEM_IS_OPEN = 1;
        $lokasi->ISITEAMITEM_STATUS = 'CLOSE';
        $lokasi->save();

        return true;
    }

    public function closeIfExpired(?Item $lokasi): bool
    {
        if (!$lokasi || !$this->isExpired($lokasi)) {
            return false;
        }

        $this->close($lokasi);

        return true;
    }

    public function closeExpiredOpenLocations(): int
    {
        $expiredLocations = Item::query()
            ->where('ISITEAMITEM_IS_OPEN', 0)
            ->whereNotNull('DATE_OPEN')
            ->where('DATE_OPEN', '<=', $this->getExpiryCutoff()->toDateTimeString())
            ->get();

        foreach ($expiredLocations as $lokasi) {
            $this->close($lokasi);
        }

        return $expiredLocations->count();
    }
}
