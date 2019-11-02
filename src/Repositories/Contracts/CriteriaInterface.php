<?php

namespace Inz\Repository\Repositories\Contracts;

interface CriteriaInterface
{
    public function withCriteria(...$criteria);
}
