<?php

namespace App\Services\Rules;

class WCAGGuideline
{
    public function __construct(
        public string $id,
        public string $description,
        public WCAGLevel $level,
        public string $url
    ) {}
}