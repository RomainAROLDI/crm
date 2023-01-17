<?php
declare(strict_types=1);

namespace App\Model;

use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Allows to send paginated data in a View with "paginated_data" variable.
 */
class PaginatedDataModel
{
    public PaginationInterface $items;
    public array $props = [];

    public function __construct(PaginationInterface $items, array $props)
    {
        $this->items = $items;
        $this->props = $props;
    }

    public function getData(): array
    {
        return [
            'items' => $this->items,
            'props' => $this->props
        ];
    }
}