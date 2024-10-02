<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected function price(): Attribute
    {
        return Attribute::make(fn (int $price) => '$' . number_format($price / 100, 2));
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
