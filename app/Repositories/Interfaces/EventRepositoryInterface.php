<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Event;
    public function add(Event $event): Event;
    public function update(Event $event): bool;
    public function delete(int $id): bool;
}