<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function getAll(): Collection
    {
        return Event::all();
    }

    public function getById(int $id): ?Event
    {
        return Event::find($id);
    }

    public function add(Event $event): Event
    {
        $event->save();
        return $event;
    }

    public function update(Event $event): bool
    {
        return $event->save();
    }

    public function delete(int $id): bool
    {
        $event = $this->getById($id);
        if (!$event) {
            return false;
        }
        return $event->delete();
    }
}