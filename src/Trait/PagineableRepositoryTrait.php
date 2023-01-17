<?php
declare(strict_types=1);

namespace App\Trait;

use Doctrine\ORM\Query;

trait PagineableRepositoryTrait
{
    /**
     * Get the query used for the pagination.
     *
     * @return Query
     */
    public function getPaginationQuery(): Query
    {
        return $this->createQueryBuilder('c')->getQuery();
    }
}