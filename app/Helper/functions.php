<?php
declare(strict_types=1);

function formatPhoneNumber(string $phone): array|string|null
{
    return preg_replace('/^\+88/', '', $phone);
}
