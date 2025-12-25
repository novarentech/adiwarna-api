<?php

namespace App\Repositories;

use App\Contracts\Repositories\DeliveryNoteItemRepositoryInterface;
use App\Models\DeliveryNoteItem;

class DeliveryNoteItemRepository extends BaseRepository implements DeliveryNoteItemRepositoryInterface
{
    protected function model(): string
    {
        return DeliveryNoteItem::class;
    }
}
