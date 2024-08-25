<?php

namespace Framework\FastRouter;

interface GenerateUri
{
    public function forRoute(string $name, array $substitutions = []): string;

}