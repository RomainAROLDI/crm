<?php
declare(strict_types=1);

namespace App\Service;

interface AppServiceInterface
{
    public function getAll();

    public function get(int $id);

    public function create();

    public function update();

    public function delete();
}