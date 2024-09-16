<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

if (!function_exists('generate_unique_pin')) {
    function generate_unique_pin(int $length = 6): string
    {
        return substr(hexdec(uniqid()), -$length);
    }
}

if (!function_exists('paginated_response')) {
    function paginated_response(LengthAwarePaginator $data): array
    {
        $paginated_array = $data->toArray();
        $rebuild = [
            'from' => $paginated_array['from'],
            'to' => $paginated_array['to'],
            'total' => $paginated_array['total'],
            'perPage' => $paginated_array['per_page'],
            'currentPage' => $paginated_array['current_page'],
            'items' => $paginated_array['data'],
            'links' => []
        ];

        foreach ($paginated_array['links'] as $link) {
            $url_split = explode('?', $link['url']);
            $url = $url_split[1] ?? null;
            $url = $link['active'] ? null : $url;
            $rebuild['links'][] = [
                'url' => $url,
                'active' => $link['active'],
                'label' => Str::contains($link['label'], ['Previous', 'Next']) ? '' : $link['label'],
            ];
        }

        return $rebuild;
    }
}

if (!function_exists('money_currency')) {
    function money_currency(float | string $number): string
    {
        return number_format($number, 2);
    }
}

