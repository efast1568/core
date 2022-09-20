<?php

namespace Waterhole\Search;

class Results
{
    public array $hits;

    public ?int $total;

    public bool $exhaustiveTotal;

    public array $channelHits;

    public ?string $error;

    public function __construct(
        array $hits,
        int $total = null,
        bool $exhaustiveTotal = false,
        array $channelHits = [],
        string $error = null,
    ) {
        $this->hits = $hits;
        $this->total = $total;
        $this->exhaustiveTotal = $exhaustiveTotal;
        $this->channelHits = $channelHits;
        $this->error = $error;
    }
}