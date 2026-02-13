<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Action key => human-readable label (used for display and filter mapping) */
    public static function actionLabels(): array
    {
        return [
            'auth.login' => 'Logged in',
            'egg_size.created' => 'Egg size added',
            'egg_size.updated' => 'Egg size updated',
            'egg_size.deleted' => 'Egg size removed',
            'egg_price.created' => 'Price added',
            'egg_price.updated' => 'Price updated',
            'egg_price.deleted' => 'Price removed',
            'inventory.updated' => 'Inventory alert updated',
            'user.created' => 'User added',
            'user.updated' => 'User updated',
            'user.deleted' => 'User removed',
            'settings.updated' => 'Settings updated',
            'stock_in.created' => 'Stock in recorded',
            'stock_out.created' => 'Sale recorded',
            'feed.created' => 'Feed added',
            'feed.updated' => 'Feed updated',
            'feed.adjusted' => 'Feed stock adjusted',
            'feed.deleted' => 'Feed removed',
        ];
    }

    /** Human-readable action label for non-technical users */
    public function getReadableActionLabelAttribute(): string
    {
        return static::actionLabels()[$this->action] ?? $this->action;
    }

    /** Property key → friendly label for Details column */
    protected static function propertyKeyLabels(): array
    {
        return [
            'size_name' => 'Size name',
            'email' => 'Email',
            'role' => 'Role',
            'egg_size_id' => 'Egg size (ID)',
            'effective_date' => 'Effective date',
            'quantity' => 'Quantity',
            'quantity_pieces' => 'Pieces',
            'total_amount' => 'Total amount',
            'total_cost' => 'Total cost',
            'minimum_stock_alert' => 'Min. stock alert',
            'updated_keys' => 'Updated fields',
        ];
    }

    /** Format a property value for display (dates, numbers, arrays) */
    protected static function formatPropertyValue($value): string
    {
        if (is_array($value)) {
            return implode(', ', array_map(function ($v) {
                return is_scalar($v) ? (string) $v : json_encode($v);
            }, $value));
        }
        if ($value instanceof \Carbon\Carbon || $value instanceof \DateTimeInterface) {
            return $value->format('M j, Y');
        }
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
            try {
                return \Carbon\Carbon::parse($value)->format('M j, Y');
            } catch (\Throwable $e) {
                return $value;
            }
        }
        if (is_numeric($value) && (int) $value == $value) {
            return number_format((int) $value);
        }
        if (is_numeric($value)) {
            return number_format((float) $value, 2);
        }
        return (string) $value;
    }

    /** Human-readable details lines for the Details column (array of "Label: value" strings) */
    public function getReadableDetailsAttribute(): array
    {
        $props = $this->properties;
        if (! is_array($props) || empty($props)) {
            return [];
        }
        $keyLabels = static::propertyKeyLabels();
        $lines = [];
        foreach ($props as $key => $value) {
            $label = $keyLabels[$key] ?? str_replace('_', ' ', ucfirst($key));
            $lines[] = $label . ': ' . static::formatPropertyValue($value);
        }
        return $lines;
    }

    public static function record(string $action, ?Model $model = null, array $properties = []): void
    {
        $user = auth()->user();
        static::create([
            'user_id' => $user?->id,
            'action' => $action,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model?->getKey(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
