<?php

namespace JiraTempoApi\Domain\Model;

class UserWorklogsMetadata
{
    /** @var integer */
    private $count;

    /** @var integer */
    private $offset;

    /** @var integer */
    private $limit;

    /** @var string */
    private $previous;

    /** @var string */
    private $next;

    public function getCount(): int
    {
        return $this->count;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }
}