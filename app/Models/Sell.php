<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sell extends Model
{
    use HasFactory;


    protected $fillable = [
        'book_id',
        'client_id',
        'price'
    ];


    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }


    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Получить список продаж с фильтрацией
     *
     * @param string|null $search Поисковый запрос
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getFilteredSales($search = null)
    {
        $query = static::with(['book', 'client']);

        if (!empty($search)) {
            $query->where('id', 'like', "%{$search}%");
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Получить статистику по продажам
     *
     * @return array
     */
    public static function getSalesStats()
    {
        return [
            'total_count' => static::count(),
            'total_amount' => static::sum('price'),
            'month_count' => static::whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->count(),
            'month_amount' => static::whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->sum('price'),
        ];
    }

    /**
     * Получить детальную информацию о продаже
     *
     * @param int $id ID продажи
     * @return Sell
     */
    public static function getSaleWithDetails($id)
    {
        return static::with(['book', 'client'])->findOrFail($id);
    }
}
